<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Type\Mixin;

use Novuso\System\Exception\DomainException;

/**
 * Trait StringOffsets
 */
trait StringOffsets
{
    /**
     * Normalizes and validates an offset
     *
     * @throws DomainException When the offset is out of bounds
     */
    protected function prepareOffset(int $offset, int $total): int
    {
        if ($offset < -$total || $offset > $total - 1) {
            $message = sprintf(
                'Offset (%d) out of range[%d, %d]',
                $offset,
                -$total,
                $total - 1
            );
            throw new DomainException($message);
        }

        if ($offset < 0) {
            $offset += $total;
        }

        return $offset;
    }

    /**
     * Normalizes and validates a length
     *
     * @throws DomainException When the length is invalid
     */
    protected function prepareLength(int $length, int $offset, int $total): int
    {
        $remainder = $total - $offset;

        if ($length === 0) {
            return $remainder;
        }

        if ($length < 0) {
            if (($length + $remainder) < 0) {
                $message = sprintf(
                    'Length (%d) out of range[%d, %d]',
                    $length,
                    -$remainder,
                    $remainder
                );
                throw new DomainException($message);
            }
            $length += $remainder;
        } else {
            if (($offset + $length) > $total) {
                return $remainder;
            }
        }

        return $length;
    }

    /**
     * Normalizes and validates a length from an offset and stop
     *
     * @throws DomainException When the stop is invalid
     */
    protected function prepareLengthFromStop(int $stop, int $offset, int $total): int
    {
        $remainder = $total - $offset;

        if ($stop === 0) {
            return $remainder;
        }

        if ($stop > 0) {
            $length = $stop - $offset;
        } else {
            $length = ($total + $stop) - $offset;
        }

        if ($length < 0) {
            $message = sprintf(
                'Stop (%d) out of range[%d, %d]',
                $stop,
                -$remainder,
                $total - 1
            );
            throw new DomainException($message);
        }

        if ($length > $remainder) {
            return $remainder;
        }

        return $length;
    }
}
