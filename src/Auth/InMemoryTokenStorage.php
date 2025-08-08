<?php

declare(strict_types=1);

namespace Avansaber\LinkedInApi\Auth;

final class InMemoryTokenStorage implements TokenStorageInterface
{
    private ?string $accessToken = null;
    private ?string $refreshToken = null;

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    public function setAccessToken(string $accessToken): void
    {
        $this->accessToken = $accessToken;
    }

    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    public function setRefreshToken(?string $refreshToken): void
    {
        $this->refreshToken = $refreshToken;
    }
}
