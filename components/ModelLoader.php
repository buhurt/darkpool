<?php

namespace app\components;


use app\helpers\StringHelper;
use stdClass;
use Yii;
use yii\db\ActiveRecord;
use yii\db\TableSchema;
use function array_key_exists;
use function get_object_vars;
use function is_array;
use function is_object;

/**
 * Загрузчик данных в модель.
 *
 * @package common\components
 */
abstract class ModelLoader
{
    /**
     * @var array Массив связей атрибутов.
     *            Ключ - удаленный атрибут.
     *            Значение - локальный атрибут.
     */
    protected $mappedAttributes = [];

    /** @var TableSchema Схема таблицы модели */
    private $tableSchema;

    /**
     * Загружает данные в модель.
     *
     * @param ActiveRecord   $model
     * @param array|stdClass $data
     *
     * @return bool
     */
    public function load(ActiveRecord $model, $data)
    {
        $this->tableSchema = Yii::$app->db->getTableSchema($model::tableName());

        if (is_array($data)) {
            $this->loadFromArray($model, $data);
        } else if (is_object($data)) {
            $this->loadFromObject($model, $data);
        } else {
            Yii::error('Unable to load mapped attributes to model ' . get_class($model) . ' from unknown data source');
            return false;
        }

        return true;
    }
    
    /**
     * Получение списка атрибутов
     * @return array
     */
    public function getAttributes() :array{
        return array_values($this->mappedAttributes);
    }

    /**
     * Загружает атрибуты модели из массива.
     *
     * @param ActiveRecord $model
     * @param array        $data
     */
    private function loadFromArray(ActiveRecord $model, $data)
    {
        foreach ($data as $key => $value) {
            if (!array_key_exists($key, $this->mappedAttributes)) {
                continue;
            }

            // получаем имя атрибута
            $localAttributeName = array_key_exists($key, $this->mappedAttributes) ? $this->mappedAttributes[$key] : $key;

            $this->setAttribute($model, $localAttributeName, $value);
        }
    }

    /**
     * Загружает атрибуты модели из объекта.
     *
     * @param ActiveRecord $model
     * @param stdClass     $data
     */
    private function loadFromObject(ActiveRecord $model, $data)
    {
        foreach (get_object_vars($data) as $property => $value) {
            if (!array_key_exists($property, $this->mappedAttributes)) {
                continue;
            }

            // получаем имя атрибута
            $localAttributeName = array_key_exists($property, $this->mappedAttributes) ?
                $this->mappedAttributes[$property] : $property;

            $this->setAttribute($model, $localAttributeName, $value);
        }
    }

    private function setAttribute(ActiveRecord $model, $attribute, $value) {
        $column = $this->tableSchema->getColumn($attribute);
        $value = StringHelper::stripQuotes($value);
        if (!empty($column)) {
            if (!(null === $value && $column->allowNull)) {

                switch ($column->type) {
                    case 'char':
                    case 'string':
                    case 'text':
                        $value = (string)$value;
                        break;
                    case 'boolean':
                        $value = (bool)$value;
                        break;
                    case 'smallint':
                    case 'integer':
                        $value = (int)$value;
                        break;
                    case 'float':
                        $value = (float)$value;
                        break;
                    case 'bigint':
                    case 'decimal':
                    case 'double':
                        $value = (double)$value;
                        break;
                    case 'datetime':
                    case 'timestamp':
                    case 'date':
                    case 'time':
                        if (!empty($value)) {
                            $value = (string)$value;
                        }
                        break;
                    // binary, money
                    default:
                        $value = (string)$value;
                }
            }
        }

        $model->$attribute = $value;
    }

}
