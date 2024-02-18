<?php


namespace app\behaviors;

use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

class UTCTimestampBehavior extends TimestampBehavior {
    protected function getValue($event) {
        if ($this->value === null) {
            return new Expression("NOW() AT TIME ZONE 'UTC'");
        }
        parent::getValue($event);
    }
}
