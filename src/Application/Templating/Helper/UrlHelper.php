<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Templating\Helper;

use Novuso\Common\Application\Routing\Exception\UrlGenerationException;
use Novuso\Common\Application\Routing\UrlGenerator;
use Novuso\Common\Application\Templating\TemplateHelper;

/**
 * Class UrlHelper
 */
final class UrlHelper implements TemplateHelper
{
    public const NAME = '_url';

    /**
     * Constructs UrlHelper
     */
    public function __construct(protected UrlGenerator $urlGenerator)
    {
    }

    /**
     * Generates a URL for the given route and parameters
     *
     * @throws UrlGenerationException
     */
    public function generate(
        string $name,
        array $parameters = [],
        array $query = [],
        bool $absolute = false
    ): string {
        return $this->urlGenerator->generate(
            $name,
            $parameters,
            $query,
            $absolute
        );
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return static::NAME;
    }
}
