<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Value\Money;

use NumberFormatter;

/**
 * Class LocaleFormatter
 */
final class LocaleFormatter implements MoneyFormatter
{
    /**
     * Constructs LocaleFormatter
     *
     * @internal
     */
    protected function __construct(protected NumberFormatter $formatter) {}

    /**
     * Creates instance from a locale string
     */
    public static function fromLocale(string $locale): static
    {
        $formatter = new NumberFormatter((string) $locale, NumberFormatter::CURRENCY);

        return new self($formatter);
    }

    /**
     * @inheritDoc
     */
    public function format(Money $money): string
    {
        $amount = $money->amount();
        $minor = $money->currency()->minor();
        $digits = $money->currency()->digits();
        $code = $money->currency()->code();
        $float = round($amount / $minor, $digits);

        return $this->formatter->formatCurrency($float, $code);
    }
}
