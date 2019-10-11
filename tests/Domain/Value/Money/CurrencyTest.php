<?php declare(strict_types=1);

namespace Novuso\Common\Test\Domain\Value\Money;

use Novuso\Common\Domain\Value\Money\Currency;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Domain\Value\Money\Currency
 */
class CurrencyTest extends UnitTestCase
{
    public function test_that_display_name_returns_expected_value()
    {
        /** @var Currency $currency */
        $currency = Currency::USD();
        $this->assertSame('US Dollar', $currency->displayName());
    }

    public function test_that_code_returns_expected_value()
    {
        /** @var Currency $currency */
        $currency = Currency::GBP();
        $this->assertSame('GBP', $currency->code());
    }

    public function test_that_numeric_code_returns_expected_value()
    {
        /** @var Currency $currency */
        $currency = Currency::EUR();
        $this->assertSame(978, $currency->numericCode());
    }

    public function test_that_digits_returns_expected_value()
    {
        /** @var Currency $currency */
        $currency = Currency::JPY();
        $this->assertSame(0, $currency->digits());
    }

    public function test_that_minor_returns_expected_value()
    {
        /** @var Currency $currency */
        $currency = Currency::IQD();
        $this->assertSame(1000, $currency->minor());
    }
}
