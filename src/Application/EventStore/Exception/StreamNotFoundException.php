<?php declare(strict_types=1);

namespace Novuso\Common\Application\EventStore\Exception;

/**
 * StreamNotFoundException is thrown when an event stream is not found
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class StreamNotFoundException extends EventStoreException
{
}
