<?php

declare(strict_types=1);

namespace Avansaber\LinkedInApi\Tests\Integration;

use Avansaber\LinkedInApi\Auth\Auth;
use Avansaber\LinkedInApi\Auth\OAuthClient;
use Avansaber\LinkedInApi\Http\ClientConfig;
use Avansaber\LinkedInApi\Http\LinkedInApiClient;
use Avansaber\LinkedInApi\Resources\Me;
use Avansaber\LinkedInApi\Tests\Fixtures\FakeHttpClient;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class OAuthAndMeIntegrationTest extends TestCase
{
    public function test_oauth_exchange_and_me_fetch(): void
    {
        $psr17 = new Psr17Factory();
        $fake = new FakeHttpClient();

        // Simulate token response
        $fake->queueResponse(new Response(200, ['Content-Type' => 'application/json'], json_encode([
            'access_token' => 'test-access',
            'expires_in' => 3600,
        ])));

        // Simulate /rest/me response
        $fake->queueResponse(new Response(200, ['Content-Type' => 'application/json'], json_encode([
            'id' => 'urn:li:person:123'
        ])));

        $auth = new Auth();
        $authUrl = $auth->getAuthUrl('id', 'https://app/callback', ['r_liteprofile'], null, 'state');
        $this->assertStringContainsString('response_type=code', $authUrl);

        // Exchange code
        $oauthClient = new OAuthClient($fake, $psr17, $psr17);
        $token = $oauthClient->exchangeAuthorizationCode('id', 'secret', 'code', 'https://app/callback');
        $this->assertSame('test-access', $token['access_token']);

        // Use token with API client
        $api = new LinkedInApiClient($fake, $psr17, $psr17, new ClientConfig('202401'), $token['access_token']);
        $me = new Me($api);
        $profile = $me->get();
        $this->assertSame('urn:li:person:123', $profile['id']);
    }
}
