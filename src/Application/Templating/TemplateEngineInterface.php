<?php declare(strict_types=1);

namespace Novuso\Common\Application\Templating;

use Novuso\Common\Application\Templating\Exception\DuplicateHelperException;
use Novuso\Common\Application\Templating\Exception\TemplatingException;

/**
 * TemplateEngineInterface is the interface for a template engine
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface TemplateEngineInterface
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
     * @param TemplateHelperInterface $helper The helper
     *
     * @return void
     *
     * @throws DuplicateHelperException When the helper name is registered
     */
    public function addHelper(TemplateHelperInterface $helper): void;

    /**
     * Checks if a template helper exists
     *
     * @param TemplateHelperInterface $helper The helper
     *
     * @return bool
     */
    public function hasHelper(TemplateHelperInterface $helper): bool;
}
