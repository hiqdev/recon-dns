<?php

namespace hiqdev\recon\dns\Model;

use yii\base\Model;

/**
 * Class Record
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 *
 * @property-read integer $refresh
 * @property-read integer $retry
 * @property-read integer $expire
 * @property-read integer $minimum
 * @property-read integer $ttl
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
            [['email'], 'email', 'enableIDN' => true],

            // Defaults
            [['ttl'], 'default', 'value' => 86400],
            [['refresh'], 'default', 'value' => 3600],
            [['retry'], 'default', 'value' => 1800],
            [['expire'], 'default', 'value' => 604800],

            // Limits
            [['ttl'], 'integer', 'min' => 60, 'max' => 86400],
        ];
    }
}
