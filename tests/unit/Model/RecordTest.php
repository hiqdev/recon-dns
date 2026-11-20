<?php

declare(strict_types=1);

namespace hiqdev\recon\dns\tests\unit\Model;

use Generator;
use hiqdev\recon\dns\Model\Record;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class RecordTest extends TestCase
{
    #[DataProvider('recordProvider')]
    public function testValidation(array $attributes, bool $valid): void
    {
        $record = new Record();
        $record->setAttributes($attributes + ['id' => 1, 'domain' => 'example.com', 'ttl' => 3600], false);

        $this->assertSame($valid, $record->validate(), implode('; ', $record->getFirstErrors()));
    }

    public static function recordProvider(): Generator
    {
        yield 'SRV with priority, weight and port' => [['type' => 'srv', 'name' => '_sip._tcp', 'value' => '10 5 5060 sip.example.com.'], true];
        yield 'SRV without weight' => [['type' => 'srv', 'name' => '_sip._tcp', 'value' => '10 5060 sip.example.com.'], false];
        yield 'SRV with four numbers' => [['type' => 'srv', 'name' => '_sip._tcp', 'value' => '10 5 5 5060 sip.example.com.'], false];

        yield 'MX with priority 10' => [['type' => 'mx', 'value' => 'mail.example.com', 'no' => 10], true];
        yield 'MX with priority 0' => [['type' => 'mx', 'value' => 'mail.example.com', 'no' => 0], false];
        yield 'MX without priority' => [['type' => 'mx', 'value' => 'mail.example.com'], false];
        yield 'A with no 0' => [['type' => 'a', 'value' => '192.0.2.1', 'no' => 0], true];

        // Zones already hold shorter TTLs: they are published as they are
        yield 'TTL shorter than 60' => [['type' => 'a', 'value' => '192.0.2.1', 'ttl' => 30], true];
        yield 'TTL longer than a day' => [['type' => 'a', 'value' => '192.0.2.1', 'ttl' => 86401], false];

        yield 'full name of 253 characters with the trailing dot' => [['type' => 'a', 'value' => '192.0.2.1', 'fqdn' => self::fqdn(253) . '.'], true];
        yield 'full name of 254 characters' => [['type' => 'a', 'value' => '192.0.2.1', 'fqdn' => self::fqdn(254)], false];
    }

    /**
     * @return string a name of the given length made of labels of up to 63 characters
     */
    private static function fqdn(int $length): string
    {
        $labels = [];
        while ($length > 0) {
            $size = min(63, $length);
            $labels[] = str_repeat('a', $size);
            $length -= $size + 1;
        }

        return implode('.', $labels);
    }
}
