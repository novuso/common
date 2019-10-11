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
     *
     * @var string
     */
    public const FILE = 'file';

    /**
     * Directory
     *
     * @var string
     */
    public const DIR = 'dir';

    /**
     * Symbolic Link
     *
     * @var string
     */
    public const LINK = 'link';

    /**
     * FIFO named pipe
     *
     * @var string
     */
    public const FIFO = 'fifo';

    /**
     * Character special device
     *
     * @var string
     */
    public const CHAR = 'char';

    /**
     * Block special device
     *
     * @var string
     */
    public const BLOCK = 'block';

    /**
     * Socket
     *
     * @var string
     */
    public const SOCKET = 'socket';

    /**
     * Unknown file type
     *
     * @var string
     */
    public const UNKNOWN = 'unknown';
}
