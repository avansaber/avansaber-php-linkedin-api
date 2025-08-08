<?php

declare(strict_types=1);

namespace Avansaber\LinkedInApi\Data\Requests;

final class CampaignCreateRequest
{
    public function __construct(
        public readonly string $accountUrn,
        public readonly string $name,
        public readonly ?string $objectiveType = null,
        public readonly ?bool $test = null,
    ) {
        if ($this->name === '') {
            throw new \InvalidArgumentException('Campaign name must not be empty');
        }
        if (!str_starts_with($this->accountUrn, 'urn:li:sponsoredAccount:')) {
            throw new \InvalidArgumentException('Account URN must start with urn:li:sponsoredAccount:');
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function toRequestBody(): array
    {
        $payload = [
            'account' => $this->accountUrn,
            'name' => $this->name,
        ];
        if ($this->objectiveType !== null) {
            $payload['objectiveType'] = $this->objectiveType;
        }
        if ($this->test !== null) {
            $payload['test'] = $this->test;
        }
        return $payload;
    }
}
