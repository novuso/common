<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Messaging\Query;

use Novuso\Common\Domain\Messaging\PayloadInterface;
use Novuso\System\Exception\DomainException;

/**
 * QueryInterface is the interface for a domain query
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface QueryInterface extends PayloadInterface
{
    /**
     * Creates instance from array representation
     *
     * @param array $data The array representation
     *
     * @return QueryInterface
     *
     * @throws DomainException When data is not valid
     */
    public static function fromArray(array $data);

    /**
     * Retrieves an array representation
     *
     * @return array
     */
    public function toArray(): array;
}
