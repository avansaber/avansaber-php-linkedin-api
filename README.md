# avansaber/php-linkedin-api

Modern, fluent, framework-agnostic PHP client for the LinkedIn API (Marketing Developer Platform).

## Installation

```bash
composer require avansaber/php-linkedin-api
```

Requires PHP >= 8.1. This is a library; do not commit composer.lock in apps consuming this library.

## Getting Started

- Create a LinkedIn Developer App and request access to the Marketing Developer Platform if needed.
- Obtain Client ID and Client Secret.
- Configure OAuth Redirect URI.

## Authentication (Authorization Code Flow, PKCE optional)

```php
use Avansaber\LinkedInApi\Auth\Auth;
use Avansaber\LinkedInApi\Auth\Pkce;
use Avansaber\LinkedInApi\Auth\Scope;

$auth = new Auth();
$state = bin2hex(random_bytes(16));
$authUrl = $auth->getAuthUrl(
    clientId: 'your-client-id',
    redirectUri: 'https://your-app/callback',
    scopes: [Scope::R_LITEPROFILE->value, Scope::W_MEMBER_SOCIAL->value],
    pkce: null, // or new Pkce($codeVerifier, $codeChallenge)
    state: $state
);
// Redirect user to $authUrl

// On callback, exchange the code for a token (perform the HTTP request using your HTTP client)
$params = $auth->getAccessToken('client-id', 'client-secret', $_GET['code'], 'https://your-app/callback', null);
// $params['endpoint'] and $params['params'] contain the token endpoint and form params
```

## Client Setup

```php
use Avansaber\LinkedInApi\Http\ClientConfig;
use Avansaber\LinkedInApi\Http\LinkedInApiClient;
use Nyholm\Psr7\Factory\Psr17Factory;
use Http\Client\Curl\Client as CurlClient; // any PSR-18 client

$psr17 = new Psr17Factory();
$http = new CurlClient();
$config = new ClientConfig(linkedInVersion: '202401');
$client = new LinkedInApiClient($http, $psr17, $psr17, $config, 'ACCESS_TOKEN');
```

## Usage Examples

### Fetch profile (Me)
```php
use Avansaber\LinkedInApi\Resources\Me;
$me = new Me($client);
$profile = $me->get(); // tries /rest/me then falls back to /v2/me
```

### Fetch organization
```php
use Avansaber\LinkedInApi\Resources\Organizations;
$orgs = new Organizations($client);
$org = $orgs->get(123456);
```

### Create UGC post
```php
use Avansaber\LinkedInApi\Resources\UgcPosts;
use Avansaber\LinkedInApi\Data\Requests\PostCreateRequest;
$ugc = new UgcPosts($client);
$resp = $ugc->create(new PostCreateRequest('urn:li:organization:123', 'Hello World'));
```

### Get post (REST)
```php
use Avansaber\LinkedInApi\Resources\Posts;
$posts = new Posts($client);
$post = $posts->get('urn:li:ugcPost:abc');
```

### SocialActions (comments/likes)
```php
use Avansaber\LinkedInApi\Resources\SocialActions;
$sa = new SocialActions($client);
$comments = $sa->comments('urn:li:ugcPost:abc');
$likes = $sa->likes('urn:li:ugcPost:abc');
```

### Pagination iterator
```php
use Avansaber\LinkedInApi\Http\PaginatorIterator;
$iter = PaginatorIterator::iterate(function(int $start, int $count) use ($client) {
    return $client->get('organizations?q=vanityName&vanityName=linkedin&start='.$start.'&count='.$count, [], false);
}, 10);
foreach ($iter as $row) { /* ... */ }
```

### Media uploads (initialize + PUT)
```php
use Avansaber\LinkedInApi\Media\MediaUploadHelper;
$helper = new MediaUploadHelper($client, $psr17);
$init = $helper->initializeImageUpload('urn:li:organization:123');
$uploadUrl = $init['value']['uploadUrl'];
$helper->uploadBinary($uploadUrl, file_get_contents('/path/image.jpg'));
```

## Error Handling

Typed exceptions are thrown on non-2xx responses:
- AuthenticationException (401)
- PermissionException (403)
- NotFoundException (404)
- ValidationException (400)
- RateLimitException (429) with Retry-After
- ServerException (5xx)

The client captures `X-LI-UUID` from responses for troubleshooting.

## Scopes & Access

- Member/profile: `r_liteprofile`, `r_emailaddress`, `w_member_social`
- Organization: `r_organization_social`, `w_organization_social`, `rw_organization_admin`
- Marketing/Ads (availability varies): `r_ads`, `rw_ads`, `r_campaigns`, `rw_campaigns`

Many Marketing Developer Platform endpoints require partner/whitelisting.

## CI & Quality

- PHPUnit test suite with retry/backoff tests
- PHPStan static analysis
- GitHub Actions matrix for PHP 8.1–8.3

## Roadmap

- Chunked media upload (videos/documents)
- More resources and request DTOs (campaign creation/update)
- Integration tests examples
