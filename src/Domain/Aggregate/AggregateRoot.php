<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Aggregate;

use Novuso\Common\Domain\Identification\Identifier;

/**
 * AggregateRoot is the base class for an aggregate root
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
abstract class AggregateRoot
{
    /**
     * Aggregate ID
     *
     * @var Identifier
     */
    protected $id;

    /**
     * Constructs AggregateRoot
     *
     * @param Identifier $id The aggregate ID
     */
    protected function __construct(Identifier $id)
    {
        $this->id = $id;
    }

    /**
     * Retrieves the ID
     *
     * @return Identifier
     */
    public function id()
    {
        return $this->id;
    }
}
