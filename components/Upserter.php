<?php

namespace app\components;

use app\helpers\DebugHelper;
use Yii;
use yii\base\BaseObject;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * Description of Upserter
 *
 */
class Upserter extends  BaseObject {
    const RESULT_ERROR = -1;

    /** @var UpsertTable Информация о таблице */
    protected $tableInfo;

    /**
     * @var CustomLoadActiveRecord Модель
     */
    protected $model;
    protected $error;

    /** @var bool Производить валидацию при добавлении модели в апсертер */
    public $validate = true;

    /**
     * Конструктор класса
     * @param UpsertTable $tableInfo Апсертер для таблицы
     * @param array $config
     */
    public function __construct(UpsertTable $tableInfo, $config = []) {
        parent::__construct($config);
        $this->tableInfo = $tableInfo;
    }

    /**
     * Добавляет модель в буфер.
     *
     * @param ActiveRecord $model
     * @return bool
     */
    public function addModel(ActiveRecord $model) {
        if ($this->validate) {
            if (!$model->validate()) {
                Yii::error('Unable to validate model ' . get_class($model) . ' due to errors: ' . DebugHelper::getModelErrorsText($model));
                return false;
            }
        }
        if (!$model->beforeSave(true)) {
            return false;
        }
        $this->model = $model;
        return true;
    }

    /**
     * Выполняет апсерт
     * @return false|null|string
     */
    public function flush() {
        if (empty($this->model)) {
            return null;
        }

        $queryString = "INSERT INTO {$this->tableInfo->tableName} ({$this->tableInfo->getInsertSectionString()}) 
                VALUES {$this->getValues()} 
                ON CONFLICT ON CONSTRAINT {$this->tableInfo->getOnConflictSectionString()} 
                DO UPDATE SET {$this->tableInfo->getUpdateSectionString()} RETURNING id";
        try {
            return Yii::$app->db->createCommand($queryString)->queryScalar(); // execute();
        } catch (\yii\db\Exception $e) {
            Yii::error('Unable to do upsert in cause of: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * @return string Строка для секции значений, содержащая данные всех моделей.
     */
    protected function getValues() {
        return $this->getModelValues($this->model, $this->tableInfo->getInsertColumnNames());
    }

    /**
     * Возвращает строку значений для отдельной модели.
     *
     * @param $model
     * @param $columnNames
     *
     * @return string
     */
    protected function getModelValues($model, $columnNames) {
        $values = array_map(function ($columnName) use ($model) {
            if ($model->$columnName instanceof Expression) {
                return $model->$columnName;
            }

            if (\is_bool($model->$columnName)) {
                return $model->$columnName ? 'true' : 'false';
            }

            if ($model->$columnName === null) {
                return 'null';
            }
            return "'" . $model->$columnName . "'";

        }, $columnNames);

        return '(' . implode(', ', $values) . ')';
    }

    /**
     * Добавление сообщения об ошибке
     * @param string $error
     */
    public function setError($error) {
        $this->error = $error;
    }

    /**
     * Получение сообщения об ошибке
     * @return string
     */
    public function getError(): string {
        return $this->error;
    }
    
    /**
     * Проверка на существование ошибок
     * @return boolean
     */
    public function hasErrors(): bool { 
        return empty($this->error);
    }

}
