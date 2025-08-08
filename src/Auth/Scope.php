<?php

declare(strict_types=1);

namespace Avansaber\LinkedInApi\Auth;

enum Scope: string
{
    case R_LITEPROFILE = 'r_liteprofile';
    case R_EMAILADDRESS = 'r_emailaddress';
    case W_MEMBER_SOCIAL = 'w_member_social';

    case R_ORGANIZATION_SOCIAL = 'r_organization_social';
    case W_ORGANIZATION_SOCIAL = 'w_organization_social';
    case RW_ORGANIZATION_ADMIN = 'rw_organization_admin';

    case R_ADS = 'r_ads';
    case RW_ADS = 'rw_ads';
    case R_CAMPAIGNS = 'r_campaigns';
    case RW_CAMPAIGNS = 'rw_campaigns';

    public static function toScopeString(self ...$scopes): string
    {
        return implode(' ', array_map(static fn(self $s) => $s->value, $scopes));
    }
}
