<?php declare(strict_types=1);

namespace Novuso\Common\Adapter\Messaging;

use FilesystemIterator;
use GlobIterator;
use Novuso\Common\Application\Filesystem\Filesystem;
use Novuso\Common\Domain\Messaging\Message;
use Novuso\Common\Domain\Messaging\MessageQueue;
use Novuso\System\Serialization\Serializer;

/**
 * FileMessageQueue is a filesystem backed message queue
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class FileMessageQueue implements MessageQueue
{
    /**
     * Filesystem
     *
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * Serializer
     *
     * @var Serializer
     */
    protected $serializer;

    /**
     * Base directory
     *
     * @var string
     */
    protected $baseDir;

    /**
     * File permissions
     *
     * @var int
     */
    protected $permissions;

    /**
     * Constructs FileMessageQueue
     *
     * @param Filesystem $filesystem  The filesystem service
     * @param Serializer $serializer  The serializer service
     * @param string     $baseDir     The base directory
     * @param int        $permissions The file permissions
     */
    public function __construct(
        Filesystem $filesystem,
        Serializer $serializer,
        string $baseDir,
        int $permissions = 0640
    ) {
        $this->filesystem = $filesystem;
        $this->serializer = $serializer;
        $this->baseDir = $baseDir;
        $this->permissions = $permissions;
    }

    /**
     * {@inheritdoc}
     */
    public function enqueue(string $channel, Message $message): void
    {
        $this->createChannel($channel);
        $directory = $this->getChannelDirectory($channel);
        $filename = $this->getMessageFileName($message);
        $content = $this->serializer->serialize($message);
        $path = sprintf('%s%s%s', $directory, DIRECTORY_SEPARATOR, $filename);
        $this->filesystem->put($path, $content);
        $this->filesystem->chmod($path, $this->permissions);
    }

    /**
     * {@inheritdoc}
     */
    public function dequeue(string $channel): ?Message
    {
        $this->createChannel($channel);
        $directory = $this->getChannelDirectory($channel);

        $filePattern = sprintf('%s%s*.message', $directory, DIRECTORY_SEPARATOR);
        $iterator = new GlobIterator($filePattern, FilesystemIterator::KEY_AS_FILENAME);
        $files = array_keys(iterator_to_array($iterator));

        $message = null;

        if ($files) {
            $fileName = array_pop($files);
            $realPath = sprintf('%s%s%s', $directory, DIRECTORY_SEPARATOR, $fileName);
            $content = $this->filesystem->get($realPath);
            /** @var Message $message */
            $message = $this->serializer->deserialize($content);
            $targetPath = sprintf('%s.process', $realPath);
            $this->filesystem->rename($realPath, $targetPath);
        }

        return $message;
    }

    /**
     * {@inheritdoc}
     */
    public function ack(string $channel, Message $message): void
    {
        $this->createChannel($channel);
        $directory = $this->getChannelDirectory($channel);
        $fileName = $this->getMessageFileName($message);
        $path = sprintf('%s%s%s.process', $directory, DIRECTORY_SEPARATOR, $fileName);

        if (!$this->filesystem->isFile($path)) {
            return;
        }

        $this->filesystem->remove($path);
    }

    /**
     * Recycles dequeued messages that have not been acknowledged
     *
     * When using the FileMessageQueue, you should have a separate script call
     * this method regularly. For instance, you might want a cron job to run
     * every few minutes depending on your needs.
     *
     * @param string $channel The channel name
     * @param int    $delay   Number of seconds before message is recycled
     *
     * @return void
     */
    public function recycleMessages(string $channel, int $delay = 600): void
    {
        $time = time();
        $this->createChannel($channel);
        $directory = $this->getChannelDirectory($channel);

        $filePattern = sprintf('%s%s*.message.process', $directory, DIRECTORY_SEPARATOR);
        $iterator = new GlobIterator($filePattern, FilesystemIterator::KEY_AS_FILENAME);
        $files = array_keys(iterator_to_array($iterator));

        foreach ($files as $fileName) {
            $realPath = sprintf('%s%s%s', $directory, DIRECTORY_SEPARATOR, $fileName);
            $aTime = $this->filesystem->lastAccessed($realPath);
            $elapsed = $time - $aTime;
            if ($elapsed > $delay) {
                $targetPath = str_replace('.message.process', '.message', $realPath);
                $this->filesystem->rename($realPath, $targetPath);
            }
        }
    }

    /**
     * Retrieves the message file name
     *
     * @param Message $message The message
     *
     * @return string
     */
    private function getMessageFileName(Message $message): string
    {
        return sprintf('%s.message', $message->id()->toString());
    }

    /**
     * Creates channel directory if needed
     *
     * @param string $channel The channel name
     *
     * @return void
     */
    private function createChannel(string $channel): void
    {
        $directory = $this->getChannelDirectory($channel);

        if ($this->filesystem->isDir($directory)) {
            return;
        }

        $this->filesystem->mkdir($directory);
    }

    /**
     * Retrieves the channel directory
     *
     * @param string $channel The channel name
     *
     * @return string
     */
    private function getChannelDirectory(string $channel): string
    {
        return sprintf(
            '%s%s%s',
            $this->baseDir,
            DIRECTORY_SEPARATOR,
            str_replace(['\\', '.'], '-', $channel)
        );
    }
}
