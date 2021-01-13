<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Application\HttpFoundation;

use Novuso\Common\Application\HttpFoundation\HttpStatus;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\HttpFoundation\HttpStatus
 */
class HttpStatusTest extends UnitTestCase
{
    public function test_that_correct_text_returned_for_status_code()
    {
        $httpStatus = HttpStatus::ENHANCE_YOUR_CALM();
        $statusText = $httpStatus->text();

        static::assertSame('Enhance Your Calm', $statusText);
    }
}
