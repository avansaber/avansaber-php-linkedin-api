<?php

declare(strict_types=1);

namespace Avansaber\LinkedInApi\Tests\Http;

use Avansaber\LinkedInApi\Http\ClientConfig;
use Avansaber\LinkedInApi\Http\LinkedInApiClient;
use Avansaber\LinkedInApi\Tests\Fixtures\FakeHttpClient;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class LinkedInApiClientTest extends TestCase
{
    public function test_get_includes_required_headers_and_decodes_json(): void
    {
        $psr17 = new Psr17Factory();
        $fake = new FakeHttpClient();
        $fake->queueResponse(new Response(200, ['Content-Type' => 'application/json', 'X-LI-UUID' => 'abc123'], json_encode(['ok' => true])));

        $client = new LinkedInApiClient(
            $fake,
            $psr17,
            $psr17,
            new ClientConfig('202401'),
            'test-token'
        );

        $result = $client->get('me');
        $this->assertSame(['ok' => true], $result);

        $req = $fake->getLastRequest();
        $this->assertNotNull($req);
        $this->assertSame('Bearer test-token', $req->getHeaderLine('Authorization'));
        $this->assertSame('application/json', $req->getHeaderLine('Accept'));
        $this->assertSame('202401', $req->getHeaderLine('LinkedIn-Version'));
        $this->assertSame('2.0.0', $req->getHeaderLine('X-Restli-Protocol-Version'));
    }

    public function test_error_mapping_rate_limit_includes_retry_after(): void
    {
        $psr17 = new Psr17Factory();
        $fake = new FakeHttpClient();
        $fake->queueResponse(new Response(429, ['Retry-After' => '10', 'Content-Type' => 'application/json'], json_encode(['message' => 'Too Many Requests'])));

        $config = new ClientConfig('202401', ClientConfig::DEFAULT_REST_BASE_URI, ClientConfig::DEFAULT_V2_BASE_URI, 30, null, 0, 1, 1);
        $client = new LinkedInApiClient($fake, $psr17, $psr17, $config, 'token');

        $this->expectException(\Avansaber\LinkedInApi\Exceptions\RateLimitException::class);
        $client->get('anything');
    }
}
