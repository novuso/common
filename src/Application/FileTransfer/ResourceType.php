<?php declare(strict_types=1);

namespace Novuso\Common\Application\FileTransfer;

use Novuso\System\Type\Enum;

/**
 * Class ResourceType
 *
 * @method static FILE
 * @method static DIR
 * @method static LINK
 * @method static FIFO
 * @method static CHAR
 * @method static BLOCK
 * @method static SOCKET
 * @method static UNKNOWN
 */
final class ResourceType extends Enum
{
    /**
     * Regular file
     */
    public const FILE = 'file';

    /**
     * Directory
     */
    public const DIR = 'dir';

    /**
     * Symbolic Link
     */
    public const LINK = 'link';

    /**
     * FIFO named pipe
     */
    public const FIFO = 'fifo';

    /**
     * Character special device
     */
    public const CHAR = 'char';

    /**
     * Block special device
     */
    public const BLOCK = 'block';

    /**
     * Socket
     */
    public const SOCKET = 'socket';

    /**
     * Unknown file type
     */
    public const UNKNOWN = 'unknown';
}
