<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Type;

/**
 * Integer is a wrapper for the int type
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class Integer extends ValueObject
{
    /**
     * Integer value
     *
     * @var int
     */
    protected $value;

    /**
     * Constructs Integer
     *
     * @param int $value The integer value
     */
    public function __construct(int $value)
    {
        $this->value = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function toString(): string
    {
        return (string) $this->value;
    }
}
