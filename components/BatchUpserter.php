<?php

namespace app\components;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Exception;
use function implode;

class BatchUpserter extends Upserter
{

    /** @var int Максимальное количество записей, накапливаемых в буфере */
    public $maxRecordCount = 1000;

    /** @var bool Перезаписывать имеющуюся модель с совпадающим ключом */
    public $rewriteExists = false;

    /** @var string Ключ модели */
    public $modelUniqueKey = null;

    /** @var int Текущее количество записей */
    protected $recordCount = 0;

    /** @var ActiveRecord[] Буфер моделей */
    protected $recordBuffer = [];

    /** @inheritDoc */
    public function __construct(UpsertTable $tableInfo, $config = [])
    {
        parent::__construct($tableInfo, $config);
    }

    /**
     * Добавляет модель в буфер.
     * @param ActiveRecord $model
     * @return bool
     */
    public function addModel(ActiveRecord $model, $without = false): bool
    {
        if (!parent::addModel($model)) {
            return false;
        }

        if (null !== $this->modelUniqueKey) {
            if (isset($this->recordBuffer[$model->{$this->modelUniqueKey}])) {

                if (!$this->rewriteExists) {
                    return true;
                }

                if ($this->rewriteExists) {
                    $this->recordCount--;
                }
            }
            $this->recordBuffer[$model->{$this->modelUniqueKey}] = $model;
        } else {
            $this->recordBuffer[] = $model;
        }
        $this->recordCount++;

        if ($this->recordCount >= $this->maxRecordCount) {
            if ($without) {
                if ($this->flushWithoutConstraint() === self::RESULT_ERROR) {
                    return false;
                }
                $this->recordCount = 0;
            } else {
                if ($this->flush() === self::RESULT_ERROR) {
                    return false;
                }
                $this->recordCount = 0;
            }
        }
        return true;
    }

    /**
     * Выполняет сброс буфера.
     */
    public function flush()
    {
        $this->setError(null);
        if ($this->recordCount === 0) {
            return 0;
        }
        $queryString = "INSERT INTO {$this->tableInfo->tableName} ({$this->tableInfo->getInsertSectionString()}) 
                        VALUES {$this->getValues()} 
                        ON CONFLICT ON CONSTRAINT {$this->tableInfo->getOnConflictSectionString()} 
                        DO UPDATE SET {$this->tableInfo->getUpdateSectionString()}";
        try {
            $this->recordBuffer = [];
            return Yii::$app->db->createCommand($queryString)->execute();
        } catch (Exception $e) {
            $error = 'Unable to do upsert in cause of: ' . $e->getMessage();
            Yii::error($error);
            $this->setError($error);
            return self::RESULT_ERROR;
        }
    }

    /**
     * Выполняет сброс буфера.
     */
    public function flushWithoutConstraint()
    {
        $this->setError(null);
        if ($this->recordCount === 0) {
            return 0;
        }
        $queryString = "INSERT INTO {$this->tableInfo->tableName} ({$this->tableInfo->getInsertSectionString()}) 
                        VALUES {$this->getValues()}";
        try {
            $this->recordBuffer = [];
            return Yii::$app->db->createCommand($queryString)->execute();
        } catch (Exception $e) {
            $error = 'Unable to do upsert in cause of: ' . $e->getMessage();
            Yii::error($error);
            $this->setError($error);
            return self::RESULT_ERROR;
        }
    }

    /**
     * @return string Строка для секции значений, содержащая данные всех моделей.
     */
    protected function getValues()
    {
        $columnNames = $this->tableInfo->getInsertColumnNames();

        $values = [];
        foreach ($this->recordBuffer as $model) {
            $values[] = $this->getModelValues($model, $columnNames);
        }

        return implode(",\n", $values);
    }

}
