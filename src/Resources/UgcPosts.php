<?php

declare(strict_types=1);

namespace Avansaber\LinkedInApi\Resources;

use Avansaber\LinkedInApi\Data\Requests\PostCreateRequest;
use Avansaber\LinkedInApi\Http\LinkedInApiClient;

final class UgcPosts
{
    public function __construct(private readonly LinkedInApiClient $client)
    {
    }

    /**
     * Create a UGC post (v2/ugcPosts). Returns the API response array.
     *
     * @return array<string, mixed>
     */
    public function create(PostCreateRequest $request): array
    {
        return $this->client->post('ugcPosts', $request->toRequestBody(), [], false);
    }
}
