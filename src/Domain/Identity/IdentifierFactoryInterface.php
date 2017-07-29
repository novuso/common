<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Identity;

/**
 * IdentifierFactory is the interface for an identifier factory
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface IdentifierFactoryInterface
{
    /**
     * Generates a unique identifier
     *
     * @return IdentifierInterface
     */
    public static function generate();
}
