<?php

use yii\db\Migration;

/**
 * Class m220319_173654_create_temp_for_west
 */
class m220319_173654_create_temp_for_west extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute('CREATE SCHEMA "temp"');
        $this->execute('COMMENT ON SCHEMA "temp" IS \'Временные\';');

        $this->createTable('temp.west_darkpool', [
            'id' => $this->primaryKey(),
            'date' => $this->date()->comment('Дата сделки'),
            'timestamp' => $this->time()->comment('Время сделки'),
            'ticker' => $this->string()->comment('Тикер'),
            'volume' => $this->integer()->comment('Кол-во бумаг (Объем)'),
            'price' => $this->float()->comment('Цена'),
            'percent_avg_30_day' => $this->float()->comment('Процент средний за 30 дней'),
            'notional' => $this->integer()->comment('Сумма (условная)'),
            'message' => $this->string()->comment('Сообщение'),
            'type' => $this->string()->comment('Тип'),
            'security_type' => $this->string()->comment('SecurityType'),
            'industry' => $this->string()->comment('Индустрия'),
            'sector' => $this->string()->comment('Сектор'),
            'deals_avg_30_day' => $this->integer()->comment('Кол-во среднее за 30 дней'),
            'float' => $this->integer()->comment('Float'),
            'earnings_date' => $this->date()->comment('Дата получения дохода'),
        ]);
        $this->addCommentOnTable('temp.west_darkpool', 'Внебиржевые сделки запада');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute('DROP SCHEMA "temp" CASCADE');
    }
}
