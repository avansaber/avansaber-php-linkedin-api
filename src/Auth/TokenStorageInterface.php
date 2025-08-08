<?php

declare(strict_types=1);

namespace Avansaber\LinkedInApi\Auth;

interface TokenStorageInterface
{
    public function getAccessToken(): ?string;
    public function setAccessToken(string $accessToken): void;

    public function getRefreshToken(): ?string;
    public function setRefreshToken(?string $refreshToken): void;
}
