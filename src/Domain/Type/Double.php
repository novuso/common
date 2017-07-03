<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Type;

/**
 * Double is a wrapper for the float type
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class Double extends ValueObject
{
    /**
     * Double value
     *
     * @var float
     */
    protected $value;

    /**
     * Constructs Double
     *
     * @param float $value The float value
     */
    public function __construct(float $value)
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
