<?php

declare(strict_types=1);

namespace Avansaber\LinkedInApi\Resources;

use Avansaber\LinkedInApi\Data\Post as PostDto;
use Avansaber\LinkedInApi\Http\LinkedInApiClient;

final class Posts
{
    public function __construct(private readonly LinkedInApiClient $client)
    {
    }

    public function get(string $postIdOrUrn): PostDto
    {
        $idPart = $postIdOrUrn;
        $data = $this->client->get('posts/' . $idPart, [], true);
        return PostDto::fromArray($data);
    }
}
