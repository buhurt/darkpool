<?php

namespace app\components;


use function array_filter;
use function array_map;
use function implode;

abstract class UpsertTable
{
    /** @var string Имя таблицы */
    public $tableName;

    /** @var UpsertColumn[] Столбцы таблицы */
    protected $columns;

    /** @var string Ограничение для условия ON CONFLICT */
    protected $onConflictConstraint;

    /**
     * @return string Строка полей для секции INSERT
     */
    final public function getInsertSectionString() {
        // получаем наименования полей
        $insertColumnNames = array_map(function(UpsertColumn $column) {
            return $column->name;
        }, $this->getInsertColumns());

        return '"'.implode('", "', $insertColumnNames).'"';
    }

    /**
     * @return UpsertColumn[] Столбцы для секции INSERT
     */
    final public function getInsertColumns() {
        return array_filter($this->columns, function(UpsertColumn $column) {
            return $column->insert;
        });
    }

    /**
     * @return array Наименования столбцов для секции INSERT
     */
    final public function getInsertColumnNames() {
        return array_map(function(UpsertColumn $column) {
            return $column->name;
        }, $this->getInsertColumns());
    }

    /**
     * @return string Строка полей для секции ON CONFLICT
     */
    final public function getOnConflictSectionString() {
        return $this->onConflictConstraint;
    }

    /**
     * @return string Строка полей для секции UPDATE
     */
    final public function getUpdateSectionString() {

        $updateColumns = array_filter($this->columns, function(UpsertColumn $column) {
            return $column->update;
        });

        $updateColumnNamesAndValues = array_map(function(UpsertColumn $column) {
            return "\"{$column->name}\" = {$column->updateValue}";
        }, $updateColumns);


        return implode(', ', $updateColumnNamesAndValues);
    }
}