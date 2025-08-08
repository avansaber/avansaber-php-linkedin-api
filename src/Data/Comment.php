<?php

declare(strict_types=1);

namespace Avansaber\LinkedInApi\Data;

final class Comment
{
    public function __construct(
        public readonly string $id,
        public readonly string $actor,
        public readonly ?string $message,
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (string) ($data['id'] ?? ''),
            actor: (string) ($data['actor'] ?? ''),
            message: isset($data['message']) ? (string) $data['message'] : null,
        );
    }
}
