<?php

namespace app\models\west;

use Exception;
use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class UploadWest extends Model
{
    /**
     * @var UploadedFile
     */
    public $csvFile;

    private $tmpDir;

    public function rules()
    {
        return [
            [['csvFile'], 'file', 'skipOnEmpty' => false],
        ];
    }

    public function attributeLabels()
    {
        return [
            'xlsFile' => 'CSV файл с данными',
        ];
    }

    public function getCsvFile()
    {
        return $this->csvFile;
    }

    public function upload()
    {
        if (!$this->checkDirExists()) {
            return false;
        }

        return $this->csvFile->saveAs($this->tmpDir . $this->csvFile->baseName . '.' . $this->csvFile->extension);
    }

    private function checkDirExists()
    {
        $this->tmpDir = Yii::getAlias(Yii::$app->runtimePath) . '/upload/';
        try {
            if (!FileHelper::createDirectory($this->tmpDir)) {
                throw new Exception('Can not create temp directory for upload file');
            }
        } catch (Exception $e) {
            Yii::error($e->getMessage() . ' in ' . $e->getFile() . ' on ' . $e->getLine() . __class__);
            return false;
        }
        return true;
    }

    public function getFileName()
    {
        return $this->tmpDir . $this->csvFile->name;
    }

    public function getTempDir()
    {
        return $this->tmpDir;
    }
}