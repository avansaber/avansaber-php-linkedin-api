<?php

declare(strict_types=1);

namespace Avansaber\LinkedInApi\Resources;

use Avansaber\LinkedInApi\Http\LinkedInApiClient;
use Avansaber\LinkedInApi\Util\Urn;

final class SocialActions
{
    public function __construct(private readonly LinkedInApiClient $client)
    {
    }

    /**
     * @return array<string, mixed>
     */
    public function comments(string $postUrn): array
    {
        $encoded = Urn::encodeForPath($postUrn);
        return $this->client->get('socialActions/' . $encoded . '/comments', [], false);
    }

    /**
     * @return array<string, mixed>
     */
    public function likes(string $postUrn): array
    {
        $encoded = Urn::encodeForPath($postUrn);
        return $this->client->get('socialActions/' . $encoded . '/likes', [], false);
    }
}
