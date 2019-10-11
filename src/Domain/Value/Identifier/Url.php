<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Value\Identifier;

use Novuso\System\Exception\DomainException;

/**
 * Class Url
 */
final class Url extends Uri
{
    /**
     * Default ports
     *
     * @var array
     */
    protected static $defaultPorts = [
        'http'  => 80,
        'https' => 443
    ];

    /**
     * Normalizes the query
     *
     * Sorts query by key and removes values without keys.
     *
     * @param string|null $query The query
     *
     * @return string|null
     *
     * @throws DomainException When the query is invalid
     */
    protected static function normalizeQuery(?string $query): ?string
    {
        if (null === $query) {
            return null;
        }

        if ('' === $query) {
            return '';
        }

        $parts = [];
        $order = [];

        // sort query params by key and remove missing keys
        foreach (explode('&', $query) as $param) {
            if ('' === $param || '=' === $param[0]) {
                continue;
            }
            $parts[] = $param;
            $kvp = explode('=', $param, 2);
            $order[] = $kvp[0];
        }

        array_multisort($order, SORT_ASC, $parts);

        return parent::normalizeQuery(implode('&', $parts));
    }
}
