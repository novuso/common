<?php declare(strict_types=1);

namespace Novuso\Common\Application\Templating;

use Novuso\Common\Application\Templating\Exception\DuplicateHelperExtension;
use Novuso\Common\Application\Templating\Exception\TemplateNotFoundException;
use Novuso\Common\Application\Templating\Exception\TemplatingException;

/**
 * PhpEngine is a template engine supporting PHP templates
 *
 * @copyright Copyright (c) 2016, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class PhpEngine implements TemplateEngine
{
    /**
     * Content block
     *
     * @var ContentBlock
     */
    protected $block;

    /**
     * Template paths
     *
     * @var string[]
     */
    protected $paths;

    /**
     * Template helpers
     *
     * @var TemplateHelper[]
     */
    protected $helpers = [];

    /**
     * Template cache
     *
     * @var array
     */
    protected $cache = [];

    /**
     * Parent templates
     *
     * @var array
     */
    protected $parents = [];

    /**
     * Parent stack
     *
     * @var array
     */
    protected $stack = [];

    /**
     * Current key
     *
     * @var string
     */
    protected $current;

    /**
     * Current file
     *
     * @var string|null
     */
    private $evalFile;

    /**
     * Current data
     *
     * @var array
     */
    private $evalData;

    /**
     * Constructs PhpEngine
     *
     * @param array $paths   A list of template paths
     * @param array $helpers A list of template helpers
     */
    public function __construct(array $paths, array $helpers = [])
    {
        $this->block = new ContentBlock();
        $this->paths = $paths;
        foreach ($helpers as $helper) {
            $this->addHelper($helper);
        }
    }

    /**
     * Retrieves a helper
     *
     * @param string $name The helper name
     *
     * @return TemplateHelper
     *
     * @throws TemplatingException When the helper is not defined
     */
    public function get(string $name): TemplateHelper
    {
        if (!isset($this->helpers[$name])) {
            $message = sprintf('Template helper "%s" is not defined', $name);
            throw new TemplatingException($message);
        }

        return $this->helpers[$name];
    }

    /**
     * Checks if a helper is defined
     *
     * @param string $name The helper name
     *
     * @return bool
     */
    public function has(string $name): bool
    {
        return isset($this->helpers[$name]);
    }

    /**
     * Extends the current template
     *
     * @param string $template The parent template
     *
     * @return void
     */
    public function extend(string $template)
    {
        $this->parents[$this->current] = $template;
    }

    /**
     * {@inheritdoc}
     */
    public function render(string $template, array $data = []): string
    {
        $file = $this->loadTemplate($template);
        $key = hash('sha256', $file);
        $this->current = $key;
        $this->parents[$key] = null;

        $content = $this->evaluate($file, $data);

        if ($this->parents[$key]) {
            $content = $this->render($this->parents[$key], $data);
        }

        return $content;
    }

    /**
     * {@inheritdoc}
     */
    public function exists(string $template): bool
    {
        foreach ($this->paths as $path) {
            $template = str_replace(':', DIRECTORY_SEPARATOR, $template);
            $file = $path.DIRECTORY_SEPARATOR.$template;
            if (is_file($file) && is_readable($file)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(string $template): bool
    {
        return pathinfo($template, PATHINFO_EXTENSION) === 'php';
    }

    /**
     * {@inheritdoc}
     */
    public function addHelper(TemplateHelper $helper)
    {
        $name = $helper->getName();

        if (isset($this->helpers[$name])) {
            throw DuplicateHelperExtension::fromName($name);
        }

        $this->helpers[$name] = $helper;
    }

    /**
     * Evaluates a PHP template
     *
     * @param string $file Template file path
     * @param array  $data Template data
     *
     * @return string
     *
     * @throws TemplatingException When data is not valid
     */
    protected function evaluate(string $file, array $data = []): string
    {
        $this->evalFile = $file;
        $this->evalData = $data;
        unset($file, $data);

        if (isset($this->evalData['this'])) {
            throw new TemplatingException('Invalid data key: this');
        }

        extract($this->evalData, EXTR_SKIP);
        $this->evalData = null;

        ob_start();
        require $this->evalFile;
        $this->evalFile = null;

        return ob_get_clean();
    }

    /**
     * Loads the given template
     *
     * @param string $template The template
     *
     * @return string
     */
    protected function loadTemplate(string $template)
    {
        if (!isset($this->cache[$template])) {
            $file = $this->getTemplatePath($template);
            $this->cache[$template] = $file;
        }

        return $this->cache[$template];
    }

    /**
     * Retrieves the absolute path to the template
     *
     * @param string $template The template
     *
     * @return string
     *
     * @throws TemplateNotFoundException When the template is not found
     */
    protected function getTemplatePath(string $template): string
    {
        foreach ($this->paths as $path) {
            $template = str_replace(':', DIRECTORY_SEPARATOR, $template);
            $file = $path.DIRECTORY_SEPARATOR.$template;
            if (is_file($file) && is_readable($file)) {
                return $file;
            }
        }
        throw TemplateNotFoundException::fromName($template);
    }
}
