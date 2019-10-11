<?php declare(strict_types=1);

namespace Novuso\Common\Application\Sms\Message;

use Novuso\Common\Domain\Value\Identifier\Url;
use Novuso\System\Exception\DomainException;

/**
 * Interface SmsFactory
 */
interface SmsFactory
{
    /**
     * Creates an SMS message
     *
     * @param string      $to        The To phone number
     * @param string      $from      The From phone number
     * @param string|null $body      The SMS message body
     * @param string[]    $mediaUrls A list of media URLs
     *
     * @return SmsMessage
     *
     * @throws DomainException When any of the URLs are not valid
     */
    public function createMessage(string $to, string $from, ?string $body = null, array $mediaUrls = []): SmsMessage;

    /**
     * Creates a media URL
     *
     * @param string $url The media URL
     *
     * @return Url
     *
     * @throws DomainException When the URL is not valid
     */
    public function createMediaUrl(string $url): Url;
}
