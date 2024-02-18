<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\services\sync\MoexSyncService;
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
class MoexController extends Controller
{
    private MoexSyncService $moexService;

    public function __construct($id, $module, $config = [])
    {
        $this->moexService = new MoexSyncService();
        parent::__construct($id, $module, $config);
    }

    /**
     * Синхронизация индексов
     */
    public function actionSyncIndex(): void
    {
        try {
            $this->moexService->getIndexes();
        } catch (InvalidConfigException|Exception $e) {
        }
    }

    /**
     * Синхронизация тикеров
     * @throws \JsonException
     */
    public function actionSyncTicker(): void
    {
        $this->moexService->getTicker();
    }

    /**
     * Синхронизация внебиржевых сделок
     */
    public function actionSyncExpit(): void
    {
        $this->moexService->getExpit();
    }


}
