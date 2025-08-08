<?php

declare(strict_types=1);

namespace Avansaber\LinkedInApi\Data;

final class Post
{
    public function __construct(
        public readonly string $id,
        public readonly string $author,
        public readonly ?string $commentary,
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (string) ($data['id'] ?? ''),
            author: (string) ($data['author'] ?? ''),
            commentary: isset($data['commentary']) ? (string) $data['commentary'] : null,
        );
    }
}
