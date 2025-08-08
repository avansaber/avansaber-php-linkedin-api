<?php

declare(strict_types=1);

namespace Avansaber\LinkedInApi\Data;

final class Organization
{
    public function __construct(
        public readonly int $id,
        public readonly ?string $localizedName,
        public readonly ?string $vanityName,
        public readonly ?string $primaryOrganizationType,
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) ($data['id'] ?? 0),
            localizedName: isset($data['localizedName']) ? (string) $data['localizedName'] : null,
            vanityName: isset($data['vanityName']) ? (string) $data['vanityName'] : null,
            primaryOrganizationType: isset($data['primaryOrganizationType']) ? (string) $data['primaryOrganizationType'] : null,
        );
    }
}
