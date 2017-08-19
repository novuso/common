<?php declare(strict_types=1);

namespace Novuso\Common\Application\Translation;

use Novuso\Common\Application\Translation\Exception\TranslationException;

/**
 * TranslatorInterface is the interface for a translator
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface TranslatorInterface
{
    /**
     * Translates the message for the given key
     *
     * @param string      $key        The message key
     * @param array       $parameters The message parameters
     * @param string|null $domain     The message domain or null for default
     * @param string|null $locale     The locale or null for default
     *
     * @return string
     *
     * @throws TranslationException When an error occurs
     */
    public function translate(
        string $key,
        array $parameters = [],
        ?string $domain = null,
        ?string $locale = null
    ): string;

    /**
     * Translates the message choice for the given key and index
     *
     * This method is generally implemented for pluralization or instances when
     * the translation may depend on some dynamic variable.
     *
     * @param string      $key        The message key
     * @param int         $index      The message index
     * @param array       $parameters The message parameters
     * @param null|string $domain     The message domain or null for default
     * @param null|string $locale     The locale or null for default
     *
     * @return string
     */
    public function choice(
        string $key,
        int $index,
        array $parameters = [],
        ?string $domain = null,
        ?string $locale = null
    ): string;
}
