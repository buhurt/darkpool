<?php

use app\components\Migration;


/**
 * Class m220313_175056_create_west_darkpool
 */
class m220313_175056_create_west_darkpool extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute('CREATE SCHEMA "west"');
        $this->execute('COMMENT ON SCHEMA "west" IS \'Западные торги\';');

        // создаем табличку security type
        $this->createTable('west.security_type', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->comment('Название'),
            'created_by' => $this->integer()->comment('Кто создал'),
            'updated_by' => $this->integer()->comment('Кто изменил'),
            'created_at' => $this->timestamp()->defaultExpression('NOW()')->comment('Дата создания'),
            'updated_at' => $this->timestamp()->comment('Дата изменения'),
        ]);
        $this->addCommentOnTable('west.security_type', 'SecurityType');
        $this->addForeignKey('west_security_type_fk_created_by', 'west.security_type', 'created_by', 'public.user', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('west_security_type_fk_updated_by', 'west.security_type', 'updated_by', 'public.user', 'id', 'SET NULL', 'CASCADE');
        $this->addConstraint('west_security_type_name_unique', 'west.security_type', ['name']);

        // создаем табличку индустрии
        $this->createTable('west.industry', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->comment('Название'),
            'name_rus' => $this->string()->comment('Название'),
            'created_by' => $this->integer()->comment('Кто создал'),
            'updated_by' => $this->integer()->comment('Кто изменил'),
            'created_at' => $this->timestamp()->defaultExpression('NOW()')->comment('Дата создания'),
            'updated_at' => $this->timestamp()->comment('Дата изменения'),
        ]);
        $this->addCommentOnTable('west.industry', 'Индустрия');
        $this->addForeignKey('west_industry_fk_created_by', 'west.industry', 'created_by', 'public.user', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('west_industry_fk_updated_by', 'west.industry', 'updated_by', 'public.user', 'id', 'SET NULL', 'CASCADE');
        $this->addConstraint('west_industry_name_unique', 'west.industry', ['name']);

        // создаем табличку сектора
        $this->createTable('west.sector', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->comment('Название'),
            'name_rus' => $this->string()->comment('Название'),
            'created_by' => $this->integer()->comment('Кто создал'),
            'updated_by' => $this->integer()->comment('Кто изменил'),
            'created_at' => $this->timestamp()->defaultExpression('NOW()')->comment('Дата создания'),
            'updated_at' => $this->timestamp()->comment('Дата изменения'),
        ]);
        $this->addCommentOnTable('west.sector', 'Сектор');
        $this->addForeignKey('west_sector_fk_created_by', 'west.sector', 'created_by', 'public.user', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('west_sector_fk_updated_by', 'west.sector', 'updated_by', 'public.user', 'id', 'SET NULL', 'CASCADE');
        $this->addConstraint('west_sector_name_unique', 'west.sector', ['name']);

        // создаем табличку тикеров
        $this->createTable('west.ticker', [
            'id' => $this->primaryKey(),
            'ticker' => $this->string()->comment('Тикер'),
            'short_name' => $this->string()->comment('Название тикера'),
            'industry_id' => $this->integer()->comment('Индустрия'),
            'sector_id' => $this->integer()->comment('Сектор'),
            'created_by' => $this->integer()->comment('Кто создал'),
            'updated_by' => $this->integer()->comment('Кто изменил'),
            'created_at' => $this->timestamp()->defaultExpression('NOW()')->comment('Дата создания'),
            'updated_at' => $this->timestamp()->comment('Дата изменения'),
        ]);
        $this->addCommentOnTable('west.ticker', 'Информация по тикеру');
        $this->addForeignKey('west_ticker_fk_industry_id', 'west.ticker', 'industry_id', 'west.industry', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('west_ticker_fk_sector_id', 'west.ticker', 'sector_id', 'west.sector', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('west_ticker_fk_created_by', 'west.ticker', 'created_by', 'public.user', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('west_ticker_fk_updated_by', 'west.ticker', 'updated_by', 'public.user', 'id', 'SET NULL', 'CASCADE');
        $this->addConstraint('west_ticker_ticker_id_unique', 'west.ticker', ['ticker']);

        $this->createTable('west.darkpool', [
            'id' => $this->primaryKey(),
            'trade_date' => $this->date()->comment('Дата сделки'),
            'trade_time' => $this->time()->comment('Время сделки'),
            'ticker_id' => $this->integer()->comment('Тикер'),
            'ticker' => $this->string()->comment('Тикер'),
            'deals_volume' => $this->integer()->comment('Кол-во бумаг (Объем)'),
            'price' => $this->float()->comment('Цена'),
            'notional' => $this->integer()->comment('Сумма (условная)'),
            'percent_avg_30_day' => $this->float()->comment('Процент средний за 30 дней'),
            'message' => $this->string()->comment('Сообщение'),
            'type' => $this->string()->comment('Тип'),
            'security_type_id' => $this->integer()->comment('SecurityType'),
            'deals_avg_30_day' => $this->integer()->comment('Кол-во среднее за 30 дней'),
            'float' => $this->integer()->comment('Float'),
            'earnings_date' => $this->date()->comment('Дата получения дохода'),
            'created_by' => $this->integer()->comment('Кто создал'),
            'updated_by' => $this->integer()->comment('Кто изменил'),
            'created_at' => $this->timestamp()->defaultExpression('NOW()')->comment('Дата создания'),
            'updated_at' => $this->timestamp()->comment('Дата изменения'),
        ]);
        $this->addCommentOnTable('west.darkpool', 'Внебиржевые сделки');
        $this->addForeignKey('west_darkpool_fk_ticker_id', 'west.darkpool', 'ticker_id', 'west.ticker', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('west_darkpool_fk_security_type_id', 'west.darkpool', 'security_type_id', 'west.security_type', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('west_darkpool_fk_created_by', 'west.darkpool', 'created_by', 'public.user', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('west_darkpool_fk_updated_by', 'west.darkpool', 'updated_by', 'public.user', 'id', 'SET NULL', 'CASCADE');
        $this->addConstraint('west_darkpool_date_ticker_unique', 'west.darkpool', ['trade_date', 'trade_time', 'deals_volume', 'notional', 'ticker_id', 'type']);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute('DROP SCHEMA "west" CASCADE');
    }
}
