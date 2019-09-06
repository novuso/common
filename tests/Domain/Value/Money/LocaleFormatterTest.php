<?php declare(strict_types=1);

namespace Novuso\Common\Test\Domain\Value\Money;

use Novuso\Common\Domain\Value\Money\LocaleFormatter;
use Novuso\Common\Domain\Value\Money\Money;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Domain\Value\Money\LocaleFormatter
 */
class LocaleFormatterTest extends UnitTestCase
{
    public function test_that_format_returns_expected_output_for_locale()
    {
        $formatter = LocaleFormatter::fromLocale('en_US');
        $this->assertSame('$1,100.75', $formatter->format(Money::USD(110075)));
    }
}
