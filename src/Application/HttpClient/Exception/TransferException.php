<?php declare(strict_types=1);

namespace Novuso\Common\Application\HttpClient\Exception;

use Novuso\System\Exception\RuntimeException;

/**
 * TransferException is the base for transfer related exceptions
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class TransferException extends RuntimeException implements Exception
{
}
