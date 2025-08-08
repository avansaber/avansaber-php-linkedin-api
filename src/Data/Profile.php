<?php

declare(strict_types=1);

namespace Avansaber\LinkedInApi\Data;

final class Profile
{
    public function __construct(
        public readonly string $id,
        public readonly ?string $firstName,
        public readonly ?string $lastName,
        public readonly ?string $localizedHeadline,
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (string) ($data['id'] ?? ''),
            firstName: isset($data['firstName']) ? (string) $data['firstName'] : null,
            lastName: isset($data['lastName']) ? (string) $data['lastName'] : null,
            localizedHeadline: isset($data['localizedHeadline']) ? (string) $data['localizedHeadline'] : null,
        );
    }
}
