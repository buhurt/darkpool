<?php

use app\components\Migration;


/**
 * Class m220209_173244_add_morningstar_source
 */
class m220209_173244_add_morningstar_source extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // создаем схему
        $this->execute('CREATE SCHEMA "morningstar"');
        $this->execute('COMMENT ON SCHEMA "morningstar" IS \'MorningStar\';');
        // создаем табличку источников
        $this->createTable('morningstar.source', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->comment('Название'),
            'url' => $this->string()->comment('Адрес API URL'),
            'apikey' => $this->string()->comment('Ключ авторизации'),
            'created_by' => $this->integer()->comment('Кто создал'),
            'updated_by' => $this->integer()->comment('Кто изменил'),
            'created_at' => $this->timestamp()->defaultExpression('NOW()')->comment('Дата создания'),
            'updated_at' => $this->timestamp()->comment('Дата изменения'),
        ]);
        $this->addCommentOnTable('morningstar.source', 'Источники синхронизации');
        $this->addForeignKey('morningstar_source_fk_created_by', 'morningstar.source', 'created_by', 'public.user', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('morningstar_source_fk_updated_by', 'morningstar.source', 'updated_by', 'public.user', 'id', 'SET NULL', 'CASCADE');

        $this->createTable('morningstar.fund', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->comment('Название'),
            'sec_id' => $this->string()->comment('ID star'),
            'created_by' => $this->integer()->comment('Кто создал'),
            'updated_by' => $this->integer()->comment('Кто изменил'),
            'created_at' => $this->timestamp()->defaultExpression('NOW()')->comment('Дата создания'),
            'updated_at' => $this->timestamp()->comment('Дата изменения'),
        ]);
        $this->addCommentOnTable('morningstar.fund', 'Фонды');
        $this->addForeignKey('morningstar_fund_fk_created_by', 'morningstar.fund', 'created_by', 'public.user', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('morningstar_fund_fk_updated_by', 'morningstar.fund', 'updated_by', 'public.user', 'id', 'SET NULL', 'CASCADE');
        $this->addConstraint('morningstar_fund_secid_unique', 'morningstar.fund', ['sec_id', 'name']);

        $this->createTable('morningstar.data', [
            'id' => $this->primaryKey(),
            'source_id' => $this->integer()->comment('Источник'),
            'fund_id' => $this->integer()->comment('Фонд'),
            'name' => $this->string()->comment('Название'),
            'current_shares' => $this->double()->comment('Текущие акции'),
            'total_shares_held' => $this->double()->comment('Общее количество принадлежащих акций'),
            'total_assets' => $this->double()->comment('Общие активы'),
            'change_amount' => $this->double()->comment('Сумма изменения'),
            'change_percentage' => $this->double()->comment('Процент изменения'),
            'date' => $this->date()->comment('Дата'),
            'created_by' => $this->integer()->comment('Кто создал'),
            'updated_by' => $this->integer()->comment('Кто изменил'),
            'created_at' => $this->timestamp()->defaultExpression('NOW()')->comment('Дата создания'),
            'updated_at' => $this->timestamp()->comment('Дата изменения'),
        ]);
        $this->addCommentOnTable('morningstar.data', 'Данные по фондам');
        $this->addForeignKey('morningstar_data_fk_source_id', 'morningstar.data', 'source_id', 'morningstar.source', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('morningstar_data_fk_fund_id', 'morningstar.data', 'fund_id', 'morningstar.fund', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('morningstar_data_fk_created_by', 'morningstar.data', 'created_by', 'public.user', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('morningstar_data_fk_updated_by', 'morningstar.data', 'updated_by', 'public.user', 'id', 'SET NULL', 'CASCADE');
        $this->addConstraint('morningstar_data_source_fund_current_shares_date_unique', 'morningstar.data', ['source_id', 'fund_id', 'current_shares', 'date']);

        $this->insert('morningstar.source', [
            'name' => 'Роснефть',
            'url' => 'https://api-global.morningstar.com/sal-service/v1/stock/ownership/v1/0P0000VPDG/OwnershipData/mutualfund/100/data?languageId=en&locale=en&clientId=MDC&component=sal-components-ownership&version=3.61.2',
            'apikey' => 'lstzFDEOhfFNMLikKa0am9mgEKLBl49T',
        ]);
        $this->insert('morningstar.source', [
            'name' => 'Магнит',
            'url' => 'https://api-global.morningstar.com/sal-service/v1/stock/ownership/v1/0P0001AJ0P/OwnershipData/mutualfund/100/data?languageId=ru&locale=ru&clientId=MDC&component=sal-components-ownership&version=3.59.1',
            'apikey' => 'lstzFDEOhfFNMLikKa0am9mgEKLBl49T',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute('DROP SCHEMA "morningstar" CASCADE');
    }

}
