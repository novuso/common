<?php declare(strict_types=1);

namespace Novuso\Common\Application\Templating;

use Novuso\Common\Application\Templating\Exception\DuplicateHelperExtension;
use Novuso\Common\Application\Templating\Exception\TemplatingException;

/**
 * TemplateEngine is the interface for a template engine
 *
 * @copyright Copyright (c) 2016, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface TemplateEngine
{
    /**
     * Renders a template
     *
     * @param string $template The template
     * @param array  $data     Template data
     *
     * @return string
     *
     * @throws TemplatingException When an error occurs
     */
    public function render(string $template, array $data = []): string;

    /**
     * Checks if a template exists
     *
     * @param string $template The template
     *
     * @return bool
     */
    public function exists(string $template): bool;

    /**
     * Checks if a template is supported
     *
     * @param string $template The template
     *
     * @return bool
     */
    public function supports(string $template): bool;

    /**
     * Adds a template helper
     *
     * @param TemplateHelper $helper The helper
     *
     * @return void
     *
     * @throws DuplicateHelperExtension When the helper name is registered
     */
    public function addHelper(TemplateHelper $helper);
}
