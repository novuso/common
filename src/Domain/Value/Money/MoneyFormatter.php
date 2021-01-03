<?php

declare(strict_types=1);

namespace Novuso\Common\Domain\Value\Money;

/**
 * Interface MoneyFormatter
 */
interface MoneyFormatter
{
    /**
     * Retrieves a formatted string for a monetary value
     */
    public function format(Money $money): string;
}
