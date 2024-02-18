<?php

namespace app\services;

use Exception;
use roopz\imap\Imap;
use yii\base\InvalidConfigException;

/**
 * Сервис для получения писем по IMAP для его дальнейшей отправки в бота
 */
class CheckEmailService
{
    const IMAP_PATH = '{IMAP.EMAIL}';
    const IMAP_LOGIN = 'login@email.ru';
    const IMAP_PASSWORD = 'password';
    /**
     *  Коннект к IMAP серверу
     */
    private Imap $connection;

    /**
     * Временная директория для хранения файлов
     */
    private string $tmpDir;

    /**
     * @throws InvalidConfigException
     */
    public function __construct()
    {
        $this->constructImap();
    }

    /**
     * Создание подключения к IMAP-серверу
     * @throws InvalidConfigException
     */
    private function constructImap(): void
    {
        $connection = [
            'imapPath' => self::IMAP_PATH . 'INBOX',
            'imapLogin' => self::IMAP_LOGIN,
            'imapPassword' => self::IMAP_PASSWORD,
            'serverEncoding' => 'utf-8',
            'attachmentsDir' => $this->tmpDir,
        ];
        $v = new Imap();
        $v->setConnection($connection);
        $this->connection = $v->getConnection();
    }

    /**
     * Инициализация
     * @throws Exception
     */
    public function init(): void
    {
        $telegram = \Yii::$app->telegram;
        $settings = \Yii::$app->db->createCommand('SELECT id, include_words, lightning FROM bot.settings WHERE id is not null ')->cache(
            86400
        )->queryOne();
        $includeWords = !empty($settings['include_words']) ? explode(';', $settings['include_words']) : null;
        $lightning = $settings['lightning'];
        //получение всех писем из папки "входящие"
        $mailIds = $this->connection->searchMailbox('UNSEEN');
        foreach ($mailIds as $mailId) {
            $isSend = false;
            //получение содержимого письма
            $mail = $this->connection->getMail($mailId);
            $subject = str_replace(array('Reuters: ', 'iFax - ', 'FW: '), '', $mail->subject);
            // проверка на молнию, если молния включена, а сообщение не капсом, то пропускаем
            if (($lightning === true) && mb_strtoupper($subject) !== $subject) {
                continue;
            }

            if (strpos($mail->subject, 'iFax') !== false) {
                $sendTitle = 'iFax: ' . $subject;
            } elseif (strpos($mail->subject, 'Reuters:') !== false) {
                $sendTitle = 'Reuters: ' . $subject;
            } elseif (strpos($mail->subject, 'FW:') !== false) {
                $sendTitle = 'BLOOM: ' . $subject;
            } else {
                $sendTitle = $mail->subject;
            }

            if ($includeWords !== null) {
                foreach ($includeWords as $includeWord) {
                    $subj = mb_strtoupper($mail->subject);
                    $word = mb_strtoupper(trim($includeWord));
                    if (!empty($word)) {
                        if (strpos($subj, $word) !== false) {
                            $isSend = true;
                            $sendTitle .= ' | ' . $word;
                            break;
                        }
                    }
                }
            } else {
                $isSend = true;
            }
            if ($isSend) {
                $telegram->sendMessage([
                    'chat_id' => 0000000,
                    'text' => $sendTitle,
                ]);
            }
        }
        unset($this->connection);
    }

    /**
     * @throws Exception
     */
    public function run(): void
    {
        $t = new self();
        $t->init();
    }


    /**
     * Удаление писем с сервера, всех до сегодня
     */
    public function deleteAll(): void
    {
        $mailIds = $this->connection->searchMailbox('BEFORE "' . date('Y-m-d') . '"');
        \Yii::info('Найдено писем: ' . count($mailIds));
        foreach ($mailIds as $mailId) {
            $this->connection->deleteMail($mailId); // Deletes all marked mails
            $this->connection->expungeDeletedMails();
        }
        \Yii::info('Удалено');
    }
}
