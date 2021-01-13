<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Application\FileTransfer;

use Mockery\MockInterface;
use Novuso\Common\Application\FileTransfer\Exception\FileTransferException;
use Novuso\Common\Application\FileTransfer\FileTransferService;
use Novuso\Common\Application\FileTransfer\FileTransport;
use Novuso\System\Exception\KeyException;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\FileTransfer\FileTransferService
 */
class FileTransferServiceTest extends UnitTestCase
{
    /** @var FileTransferService */
    protected $fileTransferService;

    protected function setUp(): void
    {
        $this->fileTransferService = new FileTransferService();
    }

    public function test_that_get_transport_returns_expected_value()
    {
        /** @var FileTransport|MockInterface $mockTransport */
        $mockTransport = $this->mock(FileTransport::class);

        $this->fileTransferService->addTransport('transport', $mockTransport);

        static::assertSame($mockTransport, $this->fileTransferService->getTransport('transport'));
    }

    public function test_that_get_transport_throws_exception_when_transport_not_found()
    {
        $this->expectException(KeyException::class);

        $this->fileTransferService->getTransport('transport');
    }

    public function test_that_add_transport_throws_exception_when_key_is_already_used()
    {
        $this->expectException(FileTransferException::class);

        /** @var FileTransport|MockInterface $mockTransport */
        $mockTransport = $this->mock(FileTransport::class);

        $this->fileTransferService->addTransport('transport', $mockTransport);
        $this->fileTransferService->addTransport('transport', $mockTransport);
    }
}
