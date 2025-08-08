<?php

declare(strict_types=1);

namespace Avansaber\LinkedInApi\Tests\Resources;

use Avansaber\LinkedInApi\Http\ClientConfig;
use Avansaber\LinkedInApi\Http\LinkedInApiClient;
use Avansaber\LinkedInApi\Resources\Me;
use Avansaber\LinkedInApi\Tests\Fixtures\FakeHttpClient;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class MeTest extends TestCase
{
    public function test_me_rest_success(): void
    {
        $psr17 = new Psr17Factory();
        $fake = new FakeHttpClient();
        $fake->queueResponse(new Response(200, ['Content-Type' => 'application/json'], json_encode(['id' => 'urn:li:person:123'])));

        $client = new LinkedInApiClient($fake, $psr17, $psr17, new ClientConfig('202401'), 'token');
        $me = new Me($client);

        $data = $me->get();
        $this->assertSame('urn:li:person:123', $data['id']);
    }

    public function test_me_fallbacks_to_v2_when_rest_fails(): void
    {
        $psr17 = new Psr17Factory();
        $fake = new FakeHttpClient();
        $fake->queueResponse(new Response(404, ['Content-Type' => 'application/json'], json_encode(['message' => 'not found'])));
        $fake->queueResponse(new Response(200, ['Content-Type' => 'application/json'], json_encode(['id' => 'urn:li:person:999'])));

        $client = new LinkedInApiClient($fake, $psr17, $psr17, new ClientConfig('202401'), 'token');
        $me = new Me($client);

        $data = $me->get();
        $this->assertSame('urn:li:person:999', $data['id']);
    }
}
