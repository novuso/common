<?php declare(strict_types=1);

namespace Novuso\Common\Application\Logging;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * SqlLogger is an SQL debug logging service
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class SqlLogger
{
    /**
     * Logger
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Log level
     *
     * @var string
     */
    protected $logLevel;

    /**
     * Constructs SqlLogger
     *
     * @param LoggerInterface $logger   The logger service
     * @param string          $logLevel The log level
     */
    public function __construct(LoggerInterface $logger, $logLevel = LogLevel::DEBUG)
    {
        $this->logger = $logger;
        $this->logLevel = $logLevel;
    }

    /**
     * Logs SQL and parameters
     *
     * @param string $sql        The SQL statement
     * @param array  $parameters The parameters
     *
     * @return void
     */
    public function log(string $sql, array $parameters = []): void
    {
        $message = sprintf('[SQL]: %s', $this->removeWhitespace($sql));
        $this->logger->log($this->logLevel, $message, $parameters);
    }

    /**
     * Removes additional whitespace from SQL
     *
     * @param string $sql The SQL statement
     *
     * @return string
     */
    private function removeWhitespace($sql): string
    {
        return preg_replace('/[\s\t\n\r]+/', ' ', trim($sql));
    }
}
