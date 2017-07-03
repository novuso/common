<?php

namespace Novuso\Test\Common\Domain\Money;

use Novuso\Common\Domain\Money\LocaleFormatter;
use Novuso\Common\Domain\Money\Money;
use Novuso\Test\System\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Domain\Money\LocaleFormatter
 */
class LocaleFormatterTest extends UnitTestCase
{
    public function test_that_format_returns_expected_output_for_locale()
    {
        $formatter = LocaleFormatter::fromLocale('en_US');
        $this->assertSame('$1,100.75', $formatter->format(Money::USD(110075)));
    }
}
