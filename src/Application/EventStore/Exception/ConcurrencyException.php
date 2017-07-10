<?php declare(strict_types=1);

namespace Novuso\Common\Application\EventStore\Exception;

/**
 * ConcurrencyException is thrown when a concurrency violation occurs
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class ConcurrencyException extends EventStoreException
{
}
