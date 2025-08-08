<?php

declare(strict_types=1);

namespace Avansaber\LinkedInApi\Tests\Http;

use Avansaber\LinkedInApi\Http\ClientConfig;
use Avansaber\LinkedInApi\Http\LinkedInApiClient;
use Avansaber\LinkedInApi\Tests\Fixtures\FakeHttpClient;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class RetryPolicyTest extends TestCase
{
    public function test_retries_on_5xx_then_succeeds(): void
    {
        $psr17 = new Psr17Factory();
        $fake = new FakeHttpClient();
        $fake->queueResponse(new Response(503, ['Content-Type' => 'application/json'], json_encode(['message' => 'unavailable'])));
        $fake->queueResponse(new Response(200, ['Content-Type' => 'application/json'], json_encode(['ok' => true])));

        $config = new ClientConfig('202401', ClientConfig::DEFAULT_REST_BASE_URI, ClientConfig::DEFAULT_V2_BASE_URI, 30, null, 2, 1, 1);
        $client = new LinkedInApiClient($fake, $psr17, $psr17, $config, 'token');

        $result = $client->get('me');
        $this->assertSame(['ok' => true], $result);
    }

    public function test_retries_on_429_then_throws_after_max(): void
    {
        $psr17 = new Psr17Factory();
        $fake = new FakeHttpClient();
        $fake->queueResponse(new Response(429, ['Retry-After' => '0', 'Content-Type' => 'application/json'], json_encode(['message' => 'rate'])));
        $fake->queueResponse(new Response(429, ['Retry-After' => '0', 'Content-Type' => 'application/json'], json_encode(['message' => 'rate'])));
        $config = new ClientConfig('202401', ClientConfig::DEFAULT_REST_BASE_URI, ClientConfig::DEFAULT_V2_BASE_URI, 30, null, 1, 1, 1);
        $client = new LinkedInApiClient($fake, $psr17, $psr17, $config, 'token');

        $this->expectException(\Avansaber\LinkedInApi\Exceptions\RateLimitException::class);
        $client->get('me');
    }
}
