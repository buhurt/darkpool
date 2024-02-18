<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\services\sync\FmpApiSyncService;
use yii\base\InvalidConfigException;
use yii\console\Controller;
use yii\httpclient\Exception;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class WestController extends Controller
{
    /**
     * Синхронизация тикеров
     */
    public function actionSyncTicker(): void
    {
        try {
            FmpApiSyncService::getTicker();
        } catch (InvalidConfigException|Exception $e) {
        }
    }

}
