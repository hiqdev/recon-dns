<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\recon\dns\Validator;

use Yii;
use yii\validators\RegularExpressionValidator;

/**
 * Class DomainValidator is used to validate domain names with a regular expression.
 */
class DomainValidator extends RegularExpressionValidator
{
    /**
     * {@inheritdoc}
     */
    public $pattern = '/^([a-z0-9][a-z0-9-]*\.)+[a-z0-9][a-z0-9-]*$/';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        if ($this->message === null) {
            $this->message = '{attribute} does not look like a valid domain name';
        }
    }

    /**
     * {@inheritdoc}
     */
    public function validateAttribute($model, $attribute)
    {
        $value = mb_strtolower($model->$attribute);
        $result = $this->validateValue($value);

        if (!empty($result)) {
            $this->addError($model, $attribute, $result[0], $result[1]);
        }
    }
}
