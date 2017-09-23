<?php declare(strict_types=1);

namespace Novuso\Common\Application\HttpClient;

use Novuso\System\Type\Enum;

/**
 * ContentType represents the encoding used for content body
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class ContentType extends Enum
{
    /**
     * Form content
     *
     * @var string
     */
    public const FORM = 'application/x-www-form-urlencoded';

    /**
     * JSON content
     *
     * @var string
     */
    public const JSON = 'application/json';
}
