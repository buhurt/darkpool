<?php

namespace app\components;

use yii\base\InvalidArgumentException;

class Formatter extends \yii\i18n\Formatter {

    protected function normalizeNumericValue($value) {
        if (empty($value)) {
            return 0;
        }
        if (strpos($value, ',') !== false) {
            $value = str_replace(',', '.', $value);
        }
        if (strpos($value, ' ') !== false) {
            $value = str_replace(' ', '', $value);
        }
        if (is_string($value) && is_numeric($value)) {
            $value = (float) $value;
        }
        if (!is_numeric($value)) {
            throw new InvalidArgumentException("'$value' is not a numeric value.");
        }

        return $value;
    }

}
