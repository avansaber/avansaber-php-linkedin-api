<?php

declare(strict_types=1);

namespace Avansaber\LinkedInApi\Tests\Http;

use Avansaber\LinkedInApi\Http\PaginatorIterator;
use PHPUnit\Framework\TestCase;

final class PaginatorIteratorTest extends TestCase
{
    public function test_iterates_two_pages_and_stops_at_total(): void
    {
        $pages = [
            ['elements' => [['i' => 1], ['i' => 2]], 'paging' => ['start' => 0, 'count' => 2, 'total' => 3]],
            ['elements' => [['i' => 3]], 'paging' => ['start' => 2, 'count' => 2, 'total' => 3]],
        ];
        $calls = 0;
        $gen = PaginatorIterator::iterate(function (int $start, int $count) use (&$pages, &$calls) {
            $calls++;
            return array_shift($pages);
        }, 2);

        $collected = [];
        foreach ($gen as $row) {
            $collected[] = $row['i'];
        }

        $this->assertSame([1, 2, 3], $collected);
        $this->assertSame(2, $calls);
    }
}
