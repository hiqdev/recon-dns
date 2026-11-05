<?php

namespace hiqdev\recon\dns\Helper;

/**
 * Class TxtHelper converts TXT record values between the stored plain text and
 * the zone presentation format.
 */
final class TxtHelper
{
    /**
     * Maximal length of a single <character-string> (RFC 1035, 3.3)
     */
    public const CHUNK_LENGTH = 255;

    /**
     * Converts a TXT value given in zone presentation format (one or more double-quoted strings,
     * e.g. `"v=spf1 -all"` or `"part1" "part2"`) into the plain text the record should contain.
     * Any other value is returned as is.
     */
    public static function normalize($value): string
    {
        $value = trim((string)$value);
        $string = '"(?:[^"\\\\]|\\\\.)*"';
        if (!preg_match('/^' . $string . '(?:\s+' . $string . ')*$/s', $value)) {
            return $value;
        }

        preg_match_all('/"((?:[^"\\\\]|\\\\.)*)"/s', $value, $matches);

        $res = '';
        foreach ($matches[1] as $part) {
            $res .= preg_replace_callback(
                '/\\\\(\d{3}|.)/s',
                static function (array $m): string {
                    return strlen($m[1]) === 3 ? chr(((int)$m[1]) % 256) : $m[1];
                },
                $part
            );
        }

        return $res;
    }

    /**
     * Renders the value as one or more double-quoted strings of at most 255 bytes,
     * with `"` and `\` escaped.
     */
    public static function toPresentation(string $value): string
    {
        $chunks = str_split(self::normalize($value), self::CHUNK_LENGTH) ?: [''];

        return implode(' ', array_map(static function (string $chunk): string {
            return '"' . addcslashes($chunk, '"\\') . '"';
        }, $chunks));
    }
}
