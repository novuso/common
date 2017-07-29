<?php declare(strict_types=1);

namespace Novuso\Common\Application\Templating;

/**
 * TemplateHelperInterface is the interface for a template helper
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface TemplateHelperInterface
{
    /**
     * Retrieves the name
     *
     * @return string
     */
    public function getName(): string;
}
