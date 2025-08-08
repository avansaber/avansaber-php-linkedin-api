<?php

declare(strict_types=1);

namespace Avansaber\LinkedInApi\Util;

final class Urn
{
    public static function isUrn(string $value): bool
    {
        return str_starts_with($value, 'urn:li:');
    }

    public static function encodeForPath(string $urn): string
    {
        return rawurlencode($urn);
    }

    /**
     * Build ids=List(...) query value with optional encoding of each id/urn.
     *
     * @param array<int, string> $ids
     */
    public static function listParam(array $ids, bool $encodeEach = true): string
    {
        $parts = array_map(static function (string $id) use ($encodeEach): string {
            return $encodeEach ? rawurlencode($id) : $id;
        }, $ids);
        return 'List(' . implode(',', $parts) . ')';
    }
}
