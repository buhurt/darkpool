<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\services\CheckEmailService;
use Exception;
use yii\console\Controller;
use yii\console\ExitCode;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HelloController extends Controller
{
    private CheckEmailService $checkEmail;

    public function __construct($id, $module, $config = [])
    {
        $this->checkEmail = new CheckEmailService();
        parent::__construct($id, $module, $config);
    }

    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionIndex(string $message = 'hello world'): int
    {
        echo $message . "\n";

        return ExitCode::OK;
    }

    /**
     * Чекалка писем в почте на заголовки
     * @throws Exception
     */
    public function actionCheckMail(): void
    {
        $this->checkEmail->run();
    }

    /**
     * Удаление с сервера писем за прошедшие дни до сегодня
     */
    public function actionDeleteMail(): void
    {
        $this->checkEmail->deleteAll();
    }

}
