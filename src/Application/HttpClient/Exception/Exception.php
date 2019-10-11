<?php declare(strict_types=1);

namespace Novuso\Common\Application\HttpClient\Exception;

use Psr\Http\Client\ClientExceptionInterface;
use Throwable;

/**
 * Interface Exception
 */
interface Exception extends ClientExceptionInterface
{
    /**
     * Retrieves the exception message
     *
     * @return string
     */
    public function getMessage();

    /**
     * Retrieves the previous exception
     *
     * @return Throwable|null
     */
    public function getPrevious();

    /**
     * Retrieves the exception code
     *
     * @return mixed
     */
    public function getCode();

    /**
     * Retrieves the file where the exception was created
     *
     * @return string
     */
    public function getFile();

    /**
     * Retrieves the line where the exception was created
     *
     * @return int
     */
    public function getLine();

    /**
     * Retrieves the stack trace
     *
     * @return array
     */
    public function getTrace();

    /**
     * Retrieves the stack trace as a string
     *
     * @return string
     */
    public function getTraceAsString();
}
