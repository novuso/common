<?php

declare(strict_types=1);

namespace Novuso\Common\Application\FileTransfer;

use Novuso\Common\Domain\Value\DateTime\DateTime;

/**
 * Class Resource
 */
final class Resource
{
    protected string $path;
    protected int $size;
    protected int $userId;
    protected int $groupId;
    protected int $mode;
    protected DateTime $accessTime;
    protected DateTime $modifyTime;
    protected ResourceType $type;

    /**
     * Constructs Resource
     */
    public function __construct(
        string $path,
        int $size,
        int $userId,
        int $groupId,
        int $mode,
        DateTime $accessTime,
        DateTime $modifyTime,
        ResourceType $type
    ) {
        $this->path = trim($path);
        $this->size = $size;
        $this->userId = $userId;
        $this->groupId = $groupId;
        $this->mode = octdec(substr(sprintf('%04o', $mode), -4));
        $this->accessTime = $accessTime;
        $this->modifyTime = $modifyTime;
        $this->type = $type;
    }

    /**
     * Retrieves the resource path
     */
    public function path(): string
    {
        return $this->path;
    }

    /**
     * Retrieves the size in bytes
     */
    public function size(): int
    {
        return $this->size;
    }

    /**
     * Retrieves the user ID
     */
    public function userId(): int
    {
        return $this->userId;
    }

    /**
     * Retrieves the group ID
     */
    public function groupId(): int
    {
        return $this->groupId;
    }

    /**
     * Retrieves the mode
     */
    public function mode(): int
    {
        return $this->mode;
    }

    /**
     * Retrieves the permissions
     */
    public function permissions(): string
    {
        return substr(sprintf('%04o', $this->mode), -4);
    }

    /**
     * Retrieves the access time
     */
    public function accessTime(): DateTime
    {
        return $this->accessTime;
    }

    /**
     * Retrieves the modify time
     */
    public function modifyTime(): DateTime
    {
        return $this->modifyTime;
    }

    /**
     * Retrieves the resource type
     */
    public function type(): ResourceType
    {
        return $this->type;
    }

    /**
     * Handles casting to a string
     */
    public function __toString(): string
    {
        return $this->path;
    }
}
