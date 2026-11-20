<?php

declare(strict_types=1);

namespace hiqdev\recon\dns\tests\unit\Helper;

use Generator;
use hiqdev\recon\dns\Helper\TxtHelper;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * The same cases are checked for the API (`dnsrecordModule::normalizeTxtValue`) and the panel.
 */
class TxtHelperTest extends TestCase
{
    #[DataProvider('normalizeProvider')]
    public function testNormalize(string $value, string $expected): void
    {
        $this->assertSame($expected, TxtHelper::normalize($value));
    }

    public static function normalizeProvider(): Generator
    {
        yield 'plain value' => ['v=spf1 -all', 'v=spf1 -all'];
        yield 'wrapped in quotes' => ['"v=spf1 a mx -all"', 'v=spf1 a mx -all'];
        yield 'several strings' => ['"v=DKIM1; k=rsa; " "p=MIGf"', 'v=DKIM1; k=rsa; p=MIGf'];
        yield 'escaped characters' => ['"say \"hi\" \\\\ ok"', 'say "hi" \\ ok'];
        yield 'decimal escape' => ['"a\059b"', 'a;b'];
        yield 'quote inside plain value' => ['v=foo "bar" baz', 'v=foo "bar" baz'];
        yield 'unbalanced quotes' => ['"v=spf1 -all', '"v=spf1 -all'];
        yield 'surrounding spaces' => ['  "x"  ', 'x'];
    }

    #[DataProvider('presentationProvider')]
    public function testToPresentation(string $value, string $expected): void
    {
        $this->assertSame($expected, TxtHelper::toPresentation($value));
    }

    public static function presentationProvider(): Generator
    {
        yield 'plain value is quoted' => ['v=spf1 -all', '"v=spf1 -all"'];
        yield 'quoted value is not quoted twice' => ['"v=spf1 -all"', '"v=spf1 -all"'];
        yield 'quote and backslash are escaped' => ['a "b" \\ c', '"a \\"b\\" \\\\ c"'];
        yield '255 characters fit in one string' => [str_repeat('x', 255), '"' . str_repeat('x', 255) . '"'];
        yield 'longer value is split by 255' => [str_repeat('x', 255) . 'yz', '"' . str_repeat('x', 255) . '" "yz"'];
        yield 'special characters are kept' => ['v=spf1 exists:%{i}.ex.com (a&b) #1 -all', '"v=spf1 exists:%{i}.ex.com (a&b) #1 -all"'];
    }
}
