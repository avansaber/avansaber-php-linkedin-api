<?php

declare(strict_types=1);

namespace Avansaber\LinkedInApi\Data\Requests;

final class PostCreateRequest
{
    public function __construct(
        public readonly string $authorUrn,
        public readonly string $commentary,
        public readonly string $visibility = 'PUBLIC'
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toRequestBody(): array
    {
        return [
            'author' => $this->authorUrn,
            'lifecycleState' => 'PUBLISHED',
            'specificContent' => [
                'com.linkedin.ugc.ShareContent' => [
                    'shareCommentary' => [
                        'text' => $this->commentary,
                    ],
                    'shareMediaCategory' => 'NONE',
                ],
            ],
            'visibility' => [
                'com.linkedin.ugc.MemberNetworkVisibility' => $this->visibility,
            ],
        ];
    }
}
