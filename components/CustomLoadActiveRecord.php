<?php

namespace app\components;

use yii\db\ActiveRecord;

/**
 * Добавляет поддержку загрузки данных в модель кастомными загрузчиками.
 *
 * @package app\components
 */
class CustomLoadActiveRecord extends ActiveRecord {

    /** @var null|ModelLoader Загрузчик данных в модель. */
    protected $loader = null;

    /**
     * Устанавливает загрузчик данных для модели.
     *
     * @param ModelLoader $loader
     */
    public function setLoader(ModelLoader $loader) {
        $this->loader = $loader;
    }

    /**
     * Отдает загрузчик
     * @return ModelLoader
     */
    public function getLoader() {
        return $this->loader;
    }

    /**
     * Загружает данные в модель.
     *
     * @param array $data
     * @param null  $formName
     *
     * @return bool
     */
    public function load($data, $formName = null) {
        if (empty($this->loader)) {
            return parent::load($data, $formName);
        }
        return $this->loader->load($this, $data);
    }
}
