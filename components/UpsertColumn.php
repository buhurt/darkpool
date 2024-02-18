<?php

namespace app\components;

/**
 * Описание столбца для upsert в таблицу.
 * @package app\components
 */
class UpsertColumn
{
    /** @var string Имя столбца */
    public $name;
    /** @var bool Использовать в значениях для INSERT */
    public $insert = true;
    /** @var bool Использовать в секции UPDATE */
    public $update = true;
    /** @var mixed Значение для секции UPDATE (по умолчанию - excluded.имя_столбца) */
    public $updateValue;
    /** @var mixed Значение по умолчанию */
    public $defaultValue;

    /**
     * @param string $name Имя столбца
     *
     * @return UpsertColumn
     */
    public static function create($name)
    {
        $column = new self();
        $column->name = $name;
        $column->updateValue = 'excluded."' . $name . '"';
        return $column;
    }

    /**
     * Выключает использование столбца в секции INSERT.
     * @return $this
     */
    public function noInsert()
    {
        $this->insert = false;
        return $this;
    }

    /**
     * Выключает использование столбца в секции UPDATE.
     *
     * @return $this
     */
    public function noUpdate()
    {
        $this->update = false;
        return $this;
    }

    /**
     * Устанавливает значение для столбца в секции UPDATE.
     *
     * @param $updateValue
     *
     * @return $this
     */
    public function updateValue($updateValue)
    {
        $this->updateValue = $updateValue;
        return $this;
    }

    /**
     * Устанавливает значение по умолчанию для поля
     * @param $value
     * @return $this
     */
    public function defaultValue($value): self {
        $this->defaultValue = $value;
        return $this;
    }
}