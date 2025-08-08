<?php

declare(strict_types=1);

namespace Avansaber\LinkedInApi\Tests\Auth;

use Avansaber\LinkedInApi\Auth\OAuthClient;
use Avansaber\LinkedInApi\Tests\Fixtures\FakeHttpClient;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class OAuthClientTest extends TestCase
{
    public function test_exchange_authorization_code(): void
    {
        $psr17 = new Psr17Factory();
        $fake = new FakeHttpClient();
        $fake->queueResponse(new Response(200, ['Content-Type' => 'application/json'], json_encode([
            'access_token' => 'abc', 'expires_in' => 3600
        ])));

        $oauth = new OAuthClient($fake, $psr17, $psr17);
        $resp = $oauth->exchangeAuthorizationCode('id', 'secret', 'code', 'https://app/callback');
        $this->assertSame('abc', $resp['access_token']);
    }

    public function test_refresh_token(): void
    {
        $psr17 = new Psr17Factory();
        $fake = new FakeHttpClient();
        $fake->queueResponse(new Response(200, ['Content-Type' => 'application/json'], json_encode([
            'access_token' => 'new', 'expires_in' => 3600
        ])));

        $oauth = new OAuthClient($fake, $psr17, $psr17);
        $resp = $oauth->refresh('id', 'secret', 'refresh');
        $this->assertSame('new', $resp['access_token']);
    }
}
