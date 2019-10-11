<?php declare(strict_types=1);

namespace Novuso\Common\Test\Application\FileTransfer;

use Novuso\Common\Application\FileTransfer\Resource;
use Novuso\Common\Application\FileTransfer\ResourceType;
use Novuso\Common\Domain\Value\DateTime\DateTime;
use Novuso\System\Test\TestCase\UnitTestCase;

/**
 * @covers \Novuso\Common\Application\FileTransfer\Resource
 */
class ResourceTest extends UnitTestCase
{
    public function test_that_path_returns_expected_value()
    {
        $path = '/path/to/file.txt';
        $size = 1024;
        $userId = 1000;
        $groupId = 1000;
        $mode = 420;
        $accessTime = DateTime::now();
        $modifyTime = DateTime::now()->modify('-1 day');
        $type = ResourceType::FILE();

        $resource = new Resource($path, $size, $userId, $groupId, $mode, $accessTime, $modifyTime, $type);

        $this->assertSame($path, $resource->path());
    }

    public function test_that_size_returns_expected_value()
    {
        $path = '/path/to/file.txt';
        $size = 1024;
        $userId = 1000;
        $groupId = 1000;
        $mode = 420;
        $accessTime = DateTime::now();
        $modifyTime = DateTime::now()->modify('-1 day');
        $type = ResourceType::FILE();

        $resource = new Resource($path, $size, $userId, $groupId, $mode, $accessTime, $modifyTime, $type);

        $this->assertSame($size, $resource->size());
    }

    public function test_that_user_id_returns_expected_value()
    {
        $path = '/path/to/file.txt';
        $size = 1024;
        $userId = 1000;
        $groupId = 1000;
        $mode = 420;
        $accessTime = DateTime::now();
        $modifyTime = DateTime::now()->modify('-1 day');
        $type = ResourceType::FILE();

        $resource = new Resource($path, $size, $userId, $groupId, $mode, $accessTime, $modifyTime, $type);

        $this->assertSame($userId, $resource->userId());
    }

    public function test_that_group_id_returns_expected_value()
    {
        $path = '/path/to/file.txt';
        $size = 1024;
        $userId = 1000;
        $groupId = 1000;
        $mode = 420;
        $accessTime = DateTime::now();
        $modifyTime = DateTime::now()->modify('-1 day');
        $type = ResourceType::FILE();

        $resource = new Resource($path, $size, $userId, $groupId, $mode, $accessTime, $modifyTime, $type);

        $this->assertSame($groupId, $resource->groupId());
    }

    public function test_that_mode_returns_expected_value()
    {
        $path = '/path/to/file.txt';
        $size = 1024;
        $userId = 1000;
        $groupId = 1000;
        $mode = 420;
        $accessTime = DateTime::now();
        $modifyTime = DateTime::now()->modify('-1 day');
        $type = ResourceType::FILE();

        $resource = new Resource($path, $size, $userId, $groupId, $mode, $accessTime, $modifyTime, $type);

        $this->assertSame($mode, $resource->mode());
    }

    public function test_that_permissions_returns_expected_value()
    {
        $path = '/path/to/file.txt';
        $size = 1024;
        $userId = 1000;
        $groupId = 1000;
        $mode = 420;
        $accessTime = DateTime::now();
        $modifyTime = DateTime::now()->modify('-1 day');
        $type = ResourceType::FILE();

        $resource = new Resource($path, $size, $userId, $groupId, $mode, $accessTime, $modifyTime, $type);

        $this->assertSame('0644', $resource->permissions());
    }

    public function test_that_access_time_returns_expected_value()
    {
        $path = '/path/to/file.txt';
        $size = 1024;
        $userId = 1000;
        $groupId = 1000;
        $mode = 420;
        $accessTime = DateTime::now();
        $modifyTime = DateTime::now()->modify('-1 day');
        $type = ResourceType::FILE();

        $resource = new Resource($path, $size, $userId, $groupId, $mode, $accessTime, $modifyTime, $type);

        $this->assertSame($accessTime, $resource->accessTime());
    }

    public function test_that_modify_time_returns_expected_value()
    {
        $path = '/path/to/file.txt';
        $size = 1024;
        $userId = 1000;
        $groupId = 1000;
        $mode = 420;
        $accessTime = DateTime::now();
        $modifyTime = DateTime::now()->modify('-1 day');
        $type = ResourceType::FILE();

        $resource = new Resource($path, $size, $userId, $groupId, $mode, $accessTime, $modifyTime, $type);

        $this->assertSame($modifyTime, $resource->modifyTime());
    }

    public function test_that_type_returns_expected_value()
    {
        $path = '/path/to/file.txt';
        $size = 1024;
        $userId = 1000;
        $groupId = 1000;
        $mode = 420;
        $accessTime = DateTime::now();
        $modifyTime = DateTime::now()->modify('-1 day');
        $type = ResourceType::FILE();

        $resource = new Resource($path, $size, $userId, $groupId, $mode, $accessTime, $modifyTime, $type);

        $this->assertSame($type, $resource->type());
    }

    public function test_that_cast_to_a_string_returns_path()
    {
        $path = '/path/to/file.txt';
        $size = 1024;
        $userId = 1000;
        $groupId = 1000;
        $mode = 420;
        $accessTime = DateTime::now();
        $modifyTime = DateTime::now()->modify('-1 day');
        $type = ResourceType::FILE();

        $resource = new Resource($path, $size, $userId, $groupId, $mode, $accessTime, $modifyTime, $type);

        $this->assertSame($path, (string) $resource);
    }
}
