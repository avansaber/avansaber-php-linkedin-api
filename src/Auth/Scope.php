<?php

declare(strict_types=1);

namespace Avansaber\LinkedInApi\Auth;

enum Scope: string
{
    // Modern OpenID Connect scopes (recommended)
    case OPENID = 'openid';
    case PROFILE = 'profile';
    case EMAIL = 'email';
    
    // Legacy scopes (deprecated but kept for backward compatibility)
    /** @deprecated Use PROFILE and OPENID instead */
    case R_LITEPROFILE = 'r_liteprofile';
    /** @deprecated Use EMAIL instead */
    case R_EMAILADDRESS = 'r_emailaddress';
    
    // Social posting scopes
    case W_MEMBER_SOCIAL = 'w_member_social';

    // Organization scopes
    case R_ORGANIZATION_SOCIAL = 'r_organization_social';
    case W_ORGANIZATION_SOCIAL = 'w_organization_social';
    case RW_ORGANIZATION_ADMIN = 'rw_organization_admin';

    // Marketing/Ads scopes (require partner access)
    case R_ADS = 'r_ads';
    case RW_ADS = 'rw_ads';
    case R_CAMPAIGNS = 'r_campaigns';
    case RW_CAMPAIGNS = 'rw_campaigns';

    public static function toScopeString(self ...$scopes): string
    {
        return implode(' ', array_map(static fn(self $s) => $s->value, $scopes));
    }

    /**
     * Get recommended modern scopes for basic profile access.
     * 
     * @return array<self>
     */
    public static function getModernProfileScopes(): array
    {
        return [self::OPENID, self::PROFILE, self::EMAIL];
    }

    /**
     * Check if this scope is deprecated.
     */
    public function isDeprecated(): bool
    {
        return in_array($this, [self::R_LITEPROFILE, self::R_EMAILADDRESS], true);
    }

    /**
     * Get the modern equivalent of a deprecated scope.
     * 
     * @return array<self>
     */
    public function getModernEquivalent(): array
    {
        return match ($this) {
            self::R_LITEPROFILE => [self::OPENID, self::PROFILE],
            self::R_EMAILADDRESS => [self::EMAIL],
            default => [$this],
        };
    }
}
