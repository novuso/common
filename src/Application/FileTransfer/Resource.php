<?php declare(strict_types=1);

namespace Novuso\Common\Application\FileTransfer;

use Novuso\Common\Domain\Value\DateTime\DateTime;

/**
 * Class Resource
 */
final class Resource
{
    /**
     * Resource path
     *
     * @var string
     */
    protected $path;

    /**
     * Size in bytes
     *
     * @var int
     */
    protected $size;

    /**
     * User ID
     *
     * @var int
     */
    protected $userId;

    /**
     * Group ID
     *
     * @var int
     */
    protected $groupId;

    /**
     * Mode
     *
     * @var int
     */
    protected $mode;

    /**
     * File access time
     *
     * @var DateTime
     */
    protected $accessTime;

    /**
     * File modify time
     *
     * @var DateTime
     */
    protected $modifyTime;

    /**
     * Resource type
     *
     * @var ResourceType
     */
    protected $type;

    /**
     * Constructs Resource
     *
     * @param string       $path       The resource path
     * @param int          $size       The size in bytes
     * @param int          $userId     The user ID
     * @param int          $groupId    The group ID
     * @param int          $mode       The mode
     * @param DateTime     $accessTime The file access time
     * @param DateTime     $modifyTime The file modify time
     * @param ResourceType $type       The resource type
     */
    public function __construct(
        string $path,
        int $size,
        int $userId,
        int $groupId,
        int $mode,
        DateTime $accessTime,
        DateTime $modifyTime,
        ResourceType $type
    ) {
        $this->path = trim($path);
        $this->size = $size;
        $this->userId = $userId;
        $this->groupId = $groupId;
        $this->mode = octdec(substr(sprintf('%04o', $mode), -4));
        $this->accessTime = $accessTime;
        $this->modifyTime = $modifyTime;
        $this->type = $type;
    }

    /**
     * Retrieves the resource path
     *
     * @return string
     */
    public function path(): string
    {
        return $this->path;
    }

    /**
     * Retrieves the size in bytes
     *
     * @return int
     */
    public function size(): int
    {
        return $this->size;
    }

    /**
     * Retrieves the user ID
     *
     * @return int
     */
    public function userId(): int
    {
        return $this->userId;
    }

    /**
     * Retrieves the group ID
     *
     * @return int
     */
    public function groupId(): int
    {
        return $this->groupId;
    }

    /**
     * Retrieves the mode
     *
     * @return int
     */
    public function mode(): int
    {
        return $this->mode;
    }

    /**
     * Retrieves the permissions
     *
     * @return string
     */
    public function permissions(): string
    {
        return substr(sprintf('%04o', $this->mode), -4);
    }

    /**
     * Retrieves the access time
     *
     * @return DateTime
     */
    public function accessTime(): DateTime
    {
        return $this->accessTime;
    }

    /**
     * Retrieves the modify time
     *
     * @return DateTime
     */
    public function modifyTime(): DateTime
    {
        return $this->modifyTime;
    }

    /**
     * Retrieves the resource type
     *
     * @return ResourceType
     */
    public function type(): ResourceType
    {
        return $this->type;
    }

    /**
     * Handles casting to a string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->path;
    }
}
