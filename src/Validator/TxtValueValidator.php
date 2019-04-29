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

use yii\validators\RegularExpressionValidator;

/**
 * Validates value of TXT record.
 */
class TxtValueValidator extends RegularExpressionValidator
{
    /**
     * {@inheritdoc}
     */
    public $pattern = '/^[a-z0-9 _.*",?{}@!=:;\/+~\\-]+$/i';
}
