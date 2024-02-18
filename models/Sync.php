<?php

namespace app\models;

use app\behaviors\UTCTimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "public.sync".
 *
 * @property int $id
 * @property string|null $name Название синки
 * @property string|null $date_start Начало
 * @property string|null $date_end Конец
 * @property int|null $status Статус
 * @property string|null $result Результат
 * @property string|null $created_at Дата создания
 * @property string|null $updated_at Дата изменения
 */
class Sync extends ActiveRecord
{
    public const SYNC_STATUS_NEW = 100;
    public const SYNC_STATUS_COMPLETE = 200;
    public const SYNC_STATUS_ERROR = 500;
    public const SYNC_TRADE_EXPIT = 'syncExpit';
    public const SYNC_WEST = 'uploadFileWest';
    public const SYNC_MORNINGSTAR = 'morningstar';

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'public.sync';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['date_start', 'date_end', 'created_at', 'updated_at', 'result'], 'safe'],
            [['status'], 'default', 'value' => null],
            [['status'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    public function behaviors(): array
    {
        return [
            UTCTimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Название синки',
            'date_start' => 'Начало',
            'date_end' => 'Конец',
            'status' => 'Статус',
            'result' => 'Результат',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата изменения',
        ];
    }

    /**
     * Получить название по коду
     * @param $code
     * @return string
     */
    public static function getStatusName($code): string
    {
        $list = static::getStatusArray();
        return array_key_exists($code, $list) ? $list[$code] : 'unknown';
    }

    /**
     * Список всех статусов
     * @return string[]
     */
    public static function getStatusArray(): array
    {
        return [
            self::SYNC_STATUS_NEW => 'Загружается...',
            self::SYNC_STATUS_COMPLETE => 'Загружено',
            self::SYNC_STATUS_ERROR => 'Ошибка',
        ];
    }

    /**
     * Получить название по коду
     * @param $code
     * @return string
     */
    public static function getName($code): string
    {
        $list = static::getNameArray();
        return array_key_exists($code, $list) ? $list[$code] : 'unknown';
    }

    /**
     * Список всех статусов
     * @return string[]
     */
    public static function getNameArray(): array
    {
        return [
            self::SYNC_TRADE_EXPIT => 'ММВБ',
            self::SYNC_WEST => 'Загрузка файла по западу',
            self::SYNC_MORNINGSTAR => 'Morningstar',
        ];
    }

    /**
     * @param $name
     * @return $this
     */
    public function create($name): Sync
    {
        date_default_timezone_set('UTC');
        $this->name = $name;
        $this->status = self::SYNC_STATUS_NEW;
        $this->date_start = date('Y-m-d H:i:s');
        $this->save();
        return $this;
    }

    /**
     * @param null $result
     * @param int $status
     */
    public function endSync($result = null, int $status = self::SYNC_STATUS_COMPLETE): void
    {
        date_default_timezone_set('UTC');
        $this->date_end = date('Y-m-d H:i:s');
        $this->status = $status;
        $this->result = $result;
        $this->save();
    }
}
