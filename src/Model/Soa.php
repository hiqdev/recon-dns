<?php

namespace hiqdev\recon\dns\Model;

use yii\base\Model;

/**
 * Class Record
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 *
 * @property-read string $refresh
 * @property-read string $retry
 * @property-read string $expire
 * @property-read string $minimum
 * @property-read string $ttl
 * @property-read string $email
 */
class Soa extends Model
{
    public $refresh;
    public $retry;
    public $expire;
    public $minimum;
    public $ttl;
    public $email;

    public function attributes()
    {
        return [
            'refresh',
            'retry',
            'expire',
            'minimum',
            'ttl',
            'email',
        ];
    }

    public function rules()
    {
        return [
            [['refresh', 'retry', 'expire', 'minimum'], 'integer'],
            /// TTL validation
            [['ttl'], 'integer', 'max' => 86400],

            [['email'], 'email'],
        ];
    }
}
