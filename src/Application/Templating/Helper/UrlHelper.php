<?php declare(strict_types=1);

namespace Novuso\Common\Application\Templating\Helper;

use Novuso\Common\Application\Routing\Exception\UrlGenerationException;
use Novuso\Common\Application\Routing\UrlGenerator;
use Novuso\Common\Application\Templating\TemplateHelper;

/**
 * Class UrlHelper
 */
final class UrlHelper implements TemplateHelper
{
    /**
     * Helper name
     *
     * @var string
     */
    public const NAME = 'url';

    /**
     * URL generator
     *
     * @var UrlGenerator
     */
    protected $urlGenerator;

    /**
     * Constructs UrlHelper
     *
     * @param UrlGenerator $urlGenerator The URL generator
     */
    public function __construct(UrlGenerator $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * Generates a URL for the given route and parameters
     *
     * @param string $name       The route name
     * @param array  $parameters An array of route parameters
     * @param array  $query      An array of query string parameters
     * @param bool   $absolute   Whether or not the URL should be absolute
     *
     * @return string
     *
     * @throws UrlGenerationException
     */
    public function generate(string $name, array $parameters = [], array $query = [], bool $absolute = false): string
    {
        return $this->urlGenerator->generate($name, $parameters, $query, $absolute);
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return static::NAME;
    }
}
