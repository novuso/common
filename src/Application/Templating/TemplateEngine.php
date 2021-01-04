<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Templating;

use Novuso\Common\Application\Templating\Exception\DuplicateHelperException;
use Novuso\Common\Application\Templating\Exception\TemplatingException;

/**
 * Interface TemplateEngine
 */
interface TemplateEngine
{
    /**
     * Renders a template
     *
     * @throws TemplatingException When an error occurs
     */
    public function render(string $template, array $data = []): string;

    /**
     * Checks if a template exists
     */
    public function exists(string $template): bool;

    /**
     * Checks if a template is supported
     */
    public function supports(string $template): bool;

    /**
     * Adds a template helper
     *
     * @throws DuplicateHelperException When the helper name is registered
     */
    public function addHelper(TemplateHelper $helper): void;

    /**
     * Checks if a template helper exists
     */
    public function hasHelper(TemplateHelper $helper): bool;
}
