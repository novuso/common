<?php declare(strict_types=1);

namespace Novuso\Common\Adapter\Templating;

use Novuso\Common\Application\Templating\Exception\DuplicateHelperException;
use Novuso\Common\Application\Templating\Exception\TemplatingException;
use Novuso\Common\Application\Templating\TemplateEngine;
use Novuso\Common\Application\Templating\TemplateHelper;
use Throwable;
use Twig_Environment;

/**
 * TwigEngine is a Twig template engine adapter
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class TwigEngine implements TemplateEngine
{
    /**
     * Twig environment
     *
     * @var Twig_Environment
     */
    protected $environment;

    /**
     * Template helpers
     *
     * @var array
     */
    protected $helpers = [];

    /**
     * TwigEngine constructor.
     *
     * @param Twig_Environment $environment The Twig environment
     */
    public function __construct(Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * {@inheritdoc}
     */
    public function render(string $template, array $data = []): string
    {
        try {
            return $this->environment->render($template, $data);
        } catch (Throwable $e) {
            throw new TemplatingException($e->getMessage(), $template, $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function exists(string $template): bool
    {
        return $this->environment->getLoader()->exists($template);
    }

    /**
     * {@inheritdoc}
     */
    public function supports(string $template): bool
    {
        return pathinfo($template, PATHINFO_EXTENSION) === 'twig';
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
        $this->environment->addGlobal($name, $helper);
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
}
