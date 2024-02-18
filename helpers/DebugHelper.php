<?php

namespace app\helpers;

use Yii;
use yii\base\Model;
use yii\db\ActiveQuery;
use yii\db\Query;

class DebugHelper {

    /**
     * Получить тело запроса
     *
     * @param Query $query
     * @throws \yii\base\ExitException
     */
    public static function showSql(Query $query): void {
        echo '<pre>';
        print_r($query->createCommand()->getRawSql());
        Yii::$app->end();
    }

    /**
     * Получить данные
     *
     * @param mixed $data
     * @throws \yii\base\ExitException
     */
    public static function show($data): void {
        echo '<pre>';
        print_r($data);
        Yii::$app->end();
    }

    /**
     * Выполнить запрос и получить результат
     *
     * @param ActiveQuery $query
     * @throws \yii\base\ExitException
     */
    public static function execSql(ActiveQuery $query): void {
        print_r($query->all());
        Yii::$app->end();
    }

    /**
     * Возвращает строку со списком ошибок валидации моделей.
     *
     * @param Model $model
     *
     * @return string
     */
    public static function getModelErrorsText(Model $model): string {
        $lines = [];
        foreach ($model->getErrors() as $errors) {
            foreach ($errors as $error) {
                $line = $error;
                if (!\in_array($line, $lines)) {
                    $lines[] = $line;
                }
            }
        }

        return implode("\n", $lines);
    }

}
