<?php

namespace app\modules\user\models;

use app\behaviors\UTCTimestampBehavior;
use Yii;
use yii\helpers\ArrayHelper;
use yii\httpclient\Client;

/**
 * This is the model class for table "public.auth_log".
 *
 * @property int $id
 * @property int|null $user_id Пользователь
 * @property string|null $created_at Создано
 * @property string|null $location Местонахождение
 * @property string|null $ip IP
 * @property string|null $user_agent Данные пользователя
 */
class AuthLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'public.auth_log';
    }

    /**
     * Создаем запись в журнале
     */
    public static function create()
    {
        $authLog = new static();
        $authLog->user_id = Yii::$app->user->id;
        $authLog->ip = Yii::$app->request->userIP;
        $authLog->user_agent = Yii::$app->request->userAgent;
        $authLog->location = self::getLocationByIp(Yii::$app->request->userIP);
        $authLog->save();
    }

    /**
     * @param $ip
     * @return string
     */
    public static function getLocationByIp($ip)
    {
        $client = new Client();
        $response = $client->createRequest()->setFormat(Client::FORMAT_JSON)->setMethod('get')->setUrl("https://suggestions.dadata.ru/suggestions/api/4_1/rs/iplocate/address?ip=$ip")->setHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Token',
            ])->send();
        if ($response->data && !empty($response->data['location'])) {
            return $response->data['location']['unrestricted_value'] ?? null;
        } else {
            $response = $client->createRequest()->setFormat(Client::FORMAT_JSON)->setMethod('get')->setUrl("http://ip-api.com/json/$ip")->setHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])->send();
            if (!empty($response)) {
                return $response->data['country'] . ', ' . $response->data['regionName'] . ', ' . $response->data['city'];
            } else return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [
                ['user_id'],
                'default',
                'value' => null,
            ],
            [
                ['user_id'],
                'integer',
            ],
            [
                ['created_at'],
                'safe',
            ],
            [
                [
                    'user_agent',
                    'location',
                ],
                'string',
            ],
            [
                ['ip'],
                'string',
                'max' => 255,
            ],
            [
                ['user_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['user_id' => 'id'],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Пользователь',
            'location' => 'Местонахождение',
            'created_at' => 'Создано',
            'ip' => 'IP',
            'user_agent' => 'Данные пользователя',
        ];
    }

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            [
                'class' => UTCTimestampBehavior::class,
                'updatedAtAttribute' => false,
            ],
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
