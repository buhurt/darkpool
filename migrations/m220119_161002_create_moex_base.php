<?php

use app\components\Migration;


/**
 * Class m220119_161002_create_moex_base
 */
class m220119_161002_create_moex_base extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
// создаем схему
        $this->execute('CREATE SCHEMA "moex"');
        $this->execute('COMMENT ON SCHEMA "moex" IS \'Биржа справочники\';');
        // создаем табличку индексов
        $this->createTable('moex.index', [
            'id' => $this->primaryKey(),
            'moex_id' => $this->string()->comment('Индекс ID'),
            'short_name' => $this->string()->comment('Название'),
            'from' => $this->date()->comment('Дата листинга'),
            'created_by' => $this->integer()->comment('Кто создал'),
            'updated_by' => $this->integer()->comment('Кто изменил'),
            'created_at' => $this->timestamp()->defaultExpression('NOW()')->comment('Дата создания'),
            'updated_at' => $this->timestamp()->comment('Дата изменения'),
        ]);
        $this->addCommentOnTable('moex.index', 'Индексы фондового рынка');
        $this->addForeignKey('moex_index_fk_created_by', 'moex.index', 'created_by', 'public.user', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('moex_index_fk_updated_by', 'moex.index', 'updated_by', 'public.user', 'id', 'SET NULL', 'CASCADE');
        $this->addConstraint('moex_index_moex_id_unique', 'moex.index', ['moex_id']);

        // создаем табличку тикеров
        $this->createTable('moex.ticker', [
            'id' => $this->primaryKey(),
            'index_id' => $this->integer()->comment('ID индекса'),
            'ticker' => $this->string()->comment('Тикер'),
            'short_name' => $this->string()->comment('Название тикера'),
            'sec_name' => $this->string()->comment('Название тикера'),
            'from' => $this->date()->comment('Дата листинга'),
            'created_by' => $this->integer()->comment('Кто создал'),
            'updated_by' => $this->integer()->comment('Кто изменил'),
            'created_at' => $this->timestamp()->defaultExpression('NOW()')->comment('Дата создания'),
            'updated_at' => $this->timestamp()->comment('Дата изменения'),
        ]);
        $this->addCommentOnTable('moex.ticker', 'Информация по тикеру');
        $this->addForeignKey('moex_ticker_fk_index_id', 'moex.ticker', 'index_id', 'moex.index', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('moex_ticker_fk_created_by', 'moex.ticker', 'created_by', 'public.user', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('moex_ticker_fk_updated_by', 'moex.ticker', 'updated_by', 'public.user', 'id', 'SET NULL', 'CASCADE');
        $this->addConstraint('moex_ticker_index_tiker_id_unique', 'moex.ticker', ['index_id', 'ticker']);

        $this->execute('CREATE SCHEMA "trade"');
        $this->execute('COMMENT ON SCHEMA "trade" IS \'Сделки\';');

        $this->createTable('trade.expit', [
            'id' => $this->primaryKey(),
            'trade_date' => $this->date()->comment('Дата отчета сделок'),
            'trade_day' => $this->string()->comment('Торговый день'),
            'ticker_id' => $this->integer()->comment('Тикер'),
            'ticker' => $this->string()->comment('Тикер'),
            'emitent_name' => $this->string()->comment('Наименование эмитента, вид ц/б'),
            'reg_num' => $this->string()->comment('Номер гос.регистрации'),
            'period_execution' => $this->string()->comment('Срок исполнения, дней'),
            'deals_count' => $this->integer()->comment('Кол-во сделок'),
            'deals_volume' => $this->float()->comment('Объем сделок, руб.'),
            'count_paper' => $this->integer()->comment('Кол-во ц/б'),
            'max_price' => $this->float()->comment('Макс. цена'),
            'min_price' => $this->float()->comment('Мин. цена'),
            'est_price' => $this->float()->comment('Рассчетная цена'),
            'big_deals' => $this->string()->comment('Крупные сделки'),
            'created_by' => $this->integer()->comment('Кто создал'),
            'updated_by' => $this->integer()->comment('Кто изменил'),
            'created_at' => $this->timestamp()->defaultExpression('NOW()')->comment('Дата создания'),
            'updated_at' => $this->timestamp()->comment('Дата изменения'),
        ]);
        $this->addCommentOnTable('trade.expit', 'Внебиржевые сделки');
        $this->addForeignKey('trade_expit_fk_ticker_id', 'trade.expit', 'ticker_id', 'moex.ticker', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('trade_expit_fk_created_by', 'trade.expit', 'created_by', 'public.user', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('trade_expit_fk_updated_by', 'trade.expit', 'updated_by', 'public.user', 'id', 'SET NULL', 'CASCADE');
        $this->addConstraint('trade_expit_date_ticker_unique', 'trade.expit', ['trade_date', 'trade_day', 'ticker_id', 'period_execution']);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute('DROP SCHEMA "trade" CASCADE');
        $this->execute('DROP SCHEMA "moex" CASCADE');
    }
}
