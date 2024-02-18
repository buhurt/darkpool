<?php

namespace app\components;

/**
 * Расширение стандартного класса миграции. Добавлены новые методы
 */
class Migration extends \yii\db\Migration {

    /**
     * Создание схемы
     * @param string $name Имя
     */
    public function createSchema($name): void {
        $this->execute("CREATE SCHEMA IF NOT EXISTS {$this->db->quoteSql($name)}");
    }

    /**
     * Удаление схемы
     * @param string $name Имя
     */
    public function dropSchema($name): void {
        $this->execute("DROP SCHEMA IF EXISTS {$this->db->quoteSql($name)} CASCADE");
    }

    /**
     * Перемещение таблицы в схему
     * @param string $table Имя таблицы
     * @param string $schema Имя схемы
     */
    public function moveToSchema($table, $schema): void {
        $this->execute("ALTER TABLE {$this->db->quoteSql($table)} SET SCHEMA {$this->db->quoteSql($schema)}");
    }

    /**
     * Добавление комментария к схеме
     * @param string $schema Схема
     * @param string $comment Комментарий
     */
    public function addCommentOnSchema($schema, $comment): void {
        $this->execute("COMMENT ON SCHEMA {$this->db->quoteSql($schema)} IS '{$this->db->quoteSql($comment)}'");
    }

    /**
     * Добавление уникального ключа (CONSTRAINT) по заданным полям
     * @param string $name Имя ключа
     * @param string $table Имя таблицы
     * @param string|array $columns Поля, по которым необходимо создать ключ
     */
    public function addConstraint($name, $table, $columns): void {
        $sql = 'ALTER TABLE ' . $this->db->quoteTableName($table)
                . ' ADD CONSTRAINT ' . $this->db->quoteColumnName($name)
                . ' UNIQUE (' . $this->db->getQueryBuilder()->buildColumns($columns) . ')';
        $this->execute($sql);
    }

    /**
     * Удаление уникального ключа (CONSTRAINT) по заданным полям
     * @param string $name Имя ключа
     * @param string $table Имя таблицы
     */
    public function dropConstraint($name, $table): void {
        $this->execute("ALTER TABLE {$this->db->quoteTableName($table)} DROP CONSTRAINT {$this->db->quoteColumnName($name)}");
    }

    /**
     * Создание представления
     * @param string $name Имя
     * @param string $text
     */
    public function createView(string $name, string $text): void {
        $this->execute("CREATE OR REPLACE VIEW {$this->db->quoteTableName($name)} AS {$this->db->quoteSql($text)}");
    }

    /**
     * Удаление представления
     * @param string $name Имя
     */
    public function dropView($name): void {
        $this->execute("DROP VIEW IF EXISTS {$this->db->quoteTableName($name)} CASCADE");
    }

}
