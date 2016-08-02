<?php declare(strict_types=1);

namespace Novuso\Common\Application\Messaging\Command\Filter;

use Novuso\Common\Domain\Messaging\Command\CommandFilter;
use Novuso\Common\Domain\Messaging\Command\CommandMessage;
use Psr\Log\LoggerInterface;

/**
 * CommandLogger is a filter that logs command messages
 *
 * @copyright Copyright (c) 2016, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class CommandLogger implements CommandFilter
{
    /**
     * Logger
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Constructs CommandLogger
     *
     * @param LoggerInterface $logger The logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function process(CommandMessage $message, callable $next)
    {
        $name = $message->payloadType()->toString();

        $this->logger->info(
            sprintf('Command received {%s}', $name),
            ['message' => $message->toString()]
        );

        $next($message);

        $this->logger->info(
            sprintf('Command handled {%s}', $name),
            ['message' => $message->toString()]
        );
    }
}
