<?php declare(strict_types=1);

namespace Novuso\Common\Application\Templating;

use Novuso\Common\Application\Templating\Exception\TemplatingException;

/**
 * ContentBlock provides template inheritance and content blocks
 *
 * @copyright Copyright (c) 2016, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class ContentBlock
{
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
     * Starts a block
     *
     * @param string $name The block name
     *
     * @return void
     *
     * @throws TemplatingException When the block is already started
     */
    public function start(string $name)
    {
        if (in_array($name, $this->openBlocks)) {
            $message = sprintf('Block "%s" is already started', $name);
            throw new TemplatingException($message);
        }

        $this->openBlocks[] = $name;
        $this->blocks[$name] = '';

        ob_start();
        ob_implicit_flush(0);
    }

    /**
     * Ends a block
     *
     * @throws TemplatingException When there is no block started
     */
    public function end()
    {
        if (!$this->openBlocks) {
            throw new TemplatingException('No block started');
        }

        $name = array_pop($this->openBlocks);

        $this->blocks[$name] = ob_get_clean();
    }

    /**
     * Sets block content
     *
     * @param string $name    The block name
     * @param string $content The block content
     *
     * @return void
     */
    public function set(string $name, string $content)
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
    public function get(string $name, string $default = null)
    {
        if (!isset($this->blocks[$name])) {
            return $default;
        }

        return $this->blocks[$name];
    }

    /**
     * Checks if a block exists
     *
     * @param string $name The block name
     *
     * @return bool
     */
    public function has(string $name): bool
    {
        return isset($this->blocks[$name]);
    }

    /**
     * Outputs a block
     *
     * @param string      $name    The block name
     * @param string|null $default The default content
     *
     * @return bool
     */
    public function output(string $name, string $default = null): bool
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
}
