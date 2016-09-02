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
     * Blocks
     *
     * @var array
     */
    protected $blocks = [];

    /**
     * Open blocks
     *
     * @var array
     */
    protected $openBlocks = [];

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
    public function extends(string $template)
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
     * Starts a block
     *
     * @param string $name The block name
     *
     * @return void
     *
     * @throws TemplatingException When the block is already started
     */
    public function startBlock(string $name)
    {
        if (in_array($name, $this->openBlocks)) {
            $message = sprintf('Block "%s" is already started', $name);
            throw new TemplatingException($message);
        }

        $this->openBlocks[] = $name;
        if (!isset($this->blocks[$name])) {
            $this->blocks[$name] = '';
        }

        ob_start();
        ob_implicit_flush(0);
    }

    /**
     * Ends a block
     *
     * @throws TemplatingException When there is no block started
     */
    public function endBlock()
    {
        if (!$this->openBlocks) {
            throw new TemplatingException('No block started');
        }

        $name = array_pop($this->openBlocks);

        $content = ob_get_clean();

        if (empty($this->blocks[$name])) {
            $this->blocks[$name] = $content;
        }

        $this->outputContent($name);
    }

    /**
     * Checks if a block exists
     *
     * @param string $name The block name
     *
     * @return bool
     */
    public function hasBlock(string $name): bool
    {
        return isset($this->blocks[$name]);
    }

    /**
     * Sets block content
     *
     * @param string $name    The block name
     * @param string $content The block content
     *
     * @return void
     */
    public function setContent(string $name, string $content)
    {
        $this->blocks[$name] = $content;
    }

    /**
     * Retrieves block content
     *
     * @param string      $name    The block name
     * @param string|null $default The default content
     *
     * @return string|null
     */
    public function getContent(string $name, string $default = null)
    {
        if (!isset($this->blocks[$name])) {
            return $default;
        }

        return $this->blocks[$name];
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
     * Outputs a block
     *
     * @param string      $name    The block name
     * @param string|null $default The default content
     *
     * @return bool
     */
    public function outputContent(string $name, string $default = null): bool
    {
        if (!isset($this->blocks[$name])) {
            if ($default !== null) {
                echo $default;

                return true;
            }

            return false;
        }

        echo $this->blocks[$name];

        return true;
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
