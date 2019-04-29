<?php

namespace hiqdev\recon\dns\Model;

use hiqdev\recon\dns\Validator\DomainPartValidator;
use hiqdev\recon\dns\Validator\FqdnValueValidator;
use hiqdev\recon\dns\Validator\MxValueValidator;
use hiqdev\recon\dns\Validator\SrvValueValidator;
use hiqdev\recon\dns\Validator\TxtValueValidator;
use Yii;
use yii\base\Model;

/**
 * Class Record
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 *
 * @property-read int $id
 * @property-read string $name
 * @property-read string $domain
 * @property-read string $type
 * @property-read string $fqdn
 * @property-read string $value
 * @property-read int $ttl
 * @property-read int $no
 */
class Record extends Model
{
    public const A     = 'a';
    public const AAAA  = 'aaaa';
    public const CNAME = 'cname';
    public const TXT   = 'txt';
    public const SOA   = 'soa';
    public const NS    = 'ns';
    public const MX    = 'mx';
    public const SRV   = 'srv';

    public function getTypes(): array
    {
        return [
            self::A,
            self::AAAA,
            self::CNAME,
            self::TXT,
            self::SOA,
            self::NS,
            self::MX,
            self::SRV,
        ];
    }

    public $id;
    public $name;
    public $domain;
    public $type;
    public $fqdn;
    public $value;
    public $ttl;
    public $no;

    public function attributes()
    {
        return [
            'id',
            'name',
            'domain',
            'type',
            'fqdn',
            'value',
            'ttl',
            'no',
        ];
    }

    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'domain', 'type', 'fqdn', 'value'], 'trim'],

            /// TTL validation
            [['ttl'], 'integer', 'max' => 86400],

            /// Name validations
            [
                ['name'],
                DomainPartValidator::class,
                'when' => $this->buildRuleWhen(['srv', 'txt', 'cname'], true),
            ],
            [
                ['name'],
                DomainPartValidator::class,
                'extended' => true,
                'when' => $this->buildRuleWhen(['srv', 'txt', 'cname']),
            ],

            [['type'], 'in', 'range' => $this->getTypes()],

            /// Value validations
            /// A
            [
                ['value'],
                'ip',
                'ipv6' => false,
                'when' => $this->buildRuleWhen('a'),
            ],

            /// AAAA
            [
                ['value'],
                'ip',
                'ipv4' => false,
                'when' => $this->buildRuleWhen('aaaa'),
            ],

            /// SOA
            [
                ['value'],
                'email',
                'when' => $this->buildRuleWhen('soa'),
            ],

            /// TXT
            [
                ['value'],
                TxtValueValidator::class,
                'when' => $this->buildRuleWhen('txt'),
            ],
            [
                ['value'],
                'string',
                'max' => 4096,
                'when' => $this->buildRuleWhen('txt'),
            ],

            /// Extract `no` for MX records
            [
                ['value'],
                MxValueValidator::class,
                'when' => $this->buildRuleWhen('mx'),
            ],

            /// NS, MX, CNAME
            [
                ['value'],
                FqdnValueValidator::class,
                'trimTrailingDot' => true,
                'when' => $this->buildRuleWhen(['ns', 'cname']),
            ],
            [
                ['value'],
                'match',
                'pattern' => '/[a-z]/i', // fqdn has no letters? Then it seems like IP
                'message' => '{attribute} is not a valid domain name',
                'when' => $this->buildRuleWhen(['ns', 'mx', 'cname']),
            ],

            /// SRV
            [
                ['value'],
                SrvValueValidator::class,
                'when' => $this->buildRuleWhen('srv'),
            ],

            /// No
            [['no'], 'integer'],

            /// For all:
            [['value', 'type', 'ttl'], 'required'],
            [['id'], 'required'],
        ];
    }

    /**
     * Builds a closure for Yii server-side validation property `when` in order to apply the rule to only a certain
     * list of DNS record types.
     *
     * @param array|string $type types, that are must be validated with the rule
     * @param bool $not inverts $type
     * @return \Closure
     */
    protected function buildRuleWhen($type, $not = false): \Closure
    {
        return function (self $model) use ($type, $not) {
            /* @var $model Record */
            return $not xor \in_array($model->type, (array)$type, true);
        };
    }
}
