<?php

declare(strict_types=1);

namespace Novuso\Common\Test\Application\FileStorage;

use Mockery\MockInterface;
use Novuso\Common\Application\FileStorage\Exception\DuplicateStorageException;
use Novuso\Common\Application\FileStorage\Exception\StorageNotFoundException;
use Novuso\Common\Application\FileStorage\FileStorage;
use Novuso\Common\Application\FileStorage\StorageService;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\FileStorage\StorageService
 */
class StorageServiceTest extends UnitTestCase
{
    /** @var StorageService */
    protected $storageService;
    /** @var FileStorage|MockInterface */
    protected $fileStorageA;
    /** @var FileStorage|MockInterface */
    protected $fileStorageB;

    protected function setUp(): void
    {
        $this->fileStorageA = $this->mock(FileStorage::class);
        $this->fileStorageB = $this->mock(FileStorage::class);
        $this->storageService = new StorageService();
        $this->storageService->addStorage('A', $this->fileStorageA);
        $this->storageService->addStorage('B', $this->fileStorageB);
    }

    public function test_that_get_storage_returns_expected_instance()
    {
        static::assertSame($this->fileStorageA, $this->storageService->getStorage('A'));
    }

    public function test_that_copy_storage_to_storage_delegates_to_storage_instances()
    {
        $path = 'path/to/some_file.txt';
        // yeah, I know; not a resource, but this is just for the mock
        $resource = new \stdClass();

        $this->fileStorageA
            ->shouldReceive('getFileResource')
            ->once()
            ->with($path)
            ->andReturn($resource);

        $this->fileStorageB
            ->shouldReceive('putFile')
            ->once()
            ->with($path, $resource)
            ->andReturnNull();

        $this->storageService->copyStorageToStorage('A', $path, 'B', $path);
    }

    public function test_that_move_storage_to_storage_delegates_to_storage_instances()
    {
        $path = 'path/to/some_file.txt';
        // yeah, I know; not a resource, but this is just for the mock
        $resource = new \stdClass();

        $this->fileStorageA
            ->shouldReceive('getFileResource')
            ->once()
            ->with($path)
            ->andReturn($resource);

        $this->fileStorageB
            ->shouldReceive('putFile')
            ->once()
            ->with($path, $resource)
            ->andReturnNull();

        $this->fileStorageA
            ->shouldReceive('removeFile')
            ->once()
            ->with($path)
            ->andReturnNull();

        $this->storageService->moveStorageToStorage('A', $path, 'B', $path);
    }

    public function test_that_get_storage_throws_exception_on_undefined_key()
    {
        $this->expectException(StorageNotFoundException::class);

        $this->storageService->getStorage('foo');
    }

    public function test_that_add_storage_throws_exception_on_duplicate_key()
    {
        $this->expectException(DuplicateStorageException::class);

        $this->storageService->addStorage('A', $this->mock(FileStorage::class));
    }
}
