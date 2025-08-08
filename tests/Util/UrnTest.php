<?php

declare(strict_types=1);

namespace Avansaber\LinkedInApi\Tests\Util;

use Avansaber\LinkedInApi\Util\Urn;
use PHPUnit\Framework\TestCase;

final class UrnTest extends TestCase
{
    public function test_is_urn_and_encode(): void
    {
        $u = 'urn:li:organization:123';
        $this->assertTrue(Urn::isUrn($u));
        $this->assertSame('urn%3Ali%3Aorganization%3A123', Urn::encodeForPath($u));
    }

    public function test_list_param_encodes_ids(): void
    {
        $out = Urn::listParam(['urn:li:post:1', 'urn:li:post:2']);
        $this->assertSame('List(urn%3Ali%3Apost%3A1,urn%3Ali%3Apost%3A2)', $out);
    }
}
