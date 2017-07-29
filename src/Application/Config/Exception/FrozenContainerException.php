<?php declare(strict_types=1);

namespace Novuso\Common\Application\Config\Exception;

use Novuso\System\Exception\ImmutableException;

/**
 * FrozenContainerException is thrown when a frozen container is modified
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class FrozenContainerException extends ImmutableException
{
}
