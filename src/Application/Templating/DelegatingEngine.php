<?php declare(strict_types=1);

namespace Novuso\Common\Application\Templating;

use Novuso\Common\Application\Templating\Exception\DuplicateHelperException;
use Novuso\Common\Application\Templating\Exception\TemplatingException;

/**
 * DelegatingEngine renders templates using a collection of engines
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class DelegatingEngine implements TemplateEngine
{
    /**
     * Template engines
     *
     * @var TemplateEngine[]
     */
    protected $engines = [];

    /**
     * Template helpers
     *
     * @var array
     */
    protected $helpers = [];

    /**
     * Constructs DelegatingEngine
     *
     * @param TemplateEngine[] $engines A list of TemplateEngine instances
     */
    public function __construct(array $engines = [])
    {
        foreach ($engines as $engine) {
            $this->addEngine($engine);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function render(string $template, array $data = []): string
    {
        $engine = $this->getEngine($template);

        foreach ($this->helpers as $helper) {
            if (!$engine->hasHelper($helper)) {
                $engine->addHelper($helper);
            }
        }

        return $engine->render($template, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function exists(string $template): bool
    {
        if (!$this->supports($template)) {
            return false;
        }

        return $this->getEngine($template)->exists($template);
    }

    /**
     * {@inheritdoc}
     */
    public function supports(string $template): bool
    {
        foreach ($this->engines as $engine) {
            if ($engine->supports($template)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function addHelper(TemplateHelper $helper): void
    {
        $name = $helper->getName();

        if (isset($this->helpers[$name])) {
            throw DuplicateHelperException::fromName($name);
        }

        $this->helpers[$name] = $helper;
    }

    /**
     * {@inheritdoc}
     */
    public function hasHelper(TemplateHelper $helper): bool
    {
        $name = $helper->getName();

        if (isset($this->helpers[$name])) {
            return true;
        }

        return false;
    }

    /**
     * Adds a template engine
     *
     * @param TemplateEngine $engine A TemplateEngine instance
     *
     * @return void
     */
    public function addEngine(TemplateEngine $engine): void
    {
        $this->engines[] = $engine;
    }

    /**
     * Resolves a template engine for the template
     *
     * @param string $template The template
     *
     * @return TemplateEngine
     *
     * @throws TemplatingException When the template is not supported
     */
    public function getEngine(string $template): TemplateEngine
    {
        foreach ($this->engines as $engine) {
            if ($engine->supports($template)) {
                return $engine;
            }
        }

        $message = sprintf('No template engines loaded to support template: %s', $template);
        throw new TemplatingException($message, $template);
    }
}
