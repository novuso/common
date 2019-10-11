<?php declare(strict_types=1);

namespace Novuso\Common\Application\Routing;

use Novuso\Common\Application\Routing\Exception\UrlGenerationException;

/**
 * Interface UrlGenerator
 */
interface UrlGenerator
{
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
     * @throws UrlGenerationException When an error occurs
     */
    public function generate(string $name, array $parameters = [], array $query = [], bool $absolute = false): string;
}
