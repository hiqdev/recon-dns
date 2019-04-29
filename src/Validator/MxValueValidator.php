<?php
/**
 * HiPanel DNS Module
 *
 * @link      https://github.com/hiqdev/hipanel-module-dns
 * @package   hipanel-module-dns
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\recon\dns\Validator;


use yii\base\Model;

/**
 * Validates value of MX record.
 */
class MxValueValidator extends FqdnValueValidator
{
    /**
     * {@inheritdoc}
     */
    public $patternWithPriority = '/^((\d+)\s)?(([a-z0-9][a-z0-9-]*\.)+[a-z0-9][a-z0-9-]*(.?))$/';

    /**
     * @var string pattern to extract
     */
    public $priorityExtractPattern = '/^((\d+)\s)?(.+)$/';

    /**
     * @var string name of attribute that represents priority
     */
    public $priorityAttribute = 'no';

    /**
     * {@inheritdoc}
     */
    public function validateAttribute($model, $attribute)
    {
        $model->$attribute = static::convertAsciiToIdn(mb_strtolower($model->$attribute));
        $this->extractPriority($model, $attribute);
        parent::validateAttribute($model, $attribute);
    }

    /**
     * Extracts priority to separated model attribute.
     *
     * @param $model Model
     * @param $attribute
     */
    public function extractPriority($model, $attribute)
    {
        $priorityAttribute = $this->priorityAttribute;
        preg_match($this->priorityExtractPattern, $model->$attribute, $matches);
        if ($matches[2] !== '') {
            $model->$priorityAttribute = $matches[2];
            $model->$attribute = $matches[3];
        }
    }
}
