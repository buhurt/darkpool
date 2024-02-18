<?php

namespace app\widgets\airdatepicker;

use Yii;
use yii\base\InvalidParamException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;


/**
 * Class DatePickerWidget
 * @package common\modules\routeam\widgets\airdatepicker
 */
class DatePickerWidget extends InputWidget
{
    public $template = '{input}';

    public $attribute1;
    public $attribute2;
    public $name1;
    public $name2;
    public $value1;
    public $value2;

    public $addInputCss = 'form-control';

    public $options = [
        'class' => 'form-control',
    ];
    public $clientOptions = [];
    public $clientEvents = [];

    public $dateFormat;

    public function init()
    {
        parent::init();
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->hasModel() ? Html::getInputId($this->model, $this->attribute) : $this->getId();
        }
        if ($this->dateFormat === null) {
            $this->dateFormat = Yii::$app->formatter->datetimeFormat;
        }
    }


    public function run()
    {
        $asset = DatePickerAsset::register($this->view);

        if (isset($this->clientOptions['language'])) {
            $lang = $this->clientOptions['language'];
            $this->view->registerJsFile($asset->baseUrl . "/js/i18n/datepicker.$lang.js", [
                'depends' => DatePickerAsset::class,
            ]);
        }

        // get formatted date value
        if ($this->hasModel()) {
            $value = Html::getAttributeValue($this->model, $this->attribute);
        } else {
            $value = $this->value;
        }
        if ($value !== null && $value !== '') {
            try {
                if (is_int($value)) {
                    $value = Yii::$app->formatter->asDatetime($value, $this->dateFormat);
                }
            } catch (InvalidParamException $e) {
                // ignore exception and keep original value if it is not a valid date
            }
        }

        $id = $this->options['id'];
        $clientOptions = empty($this->clientOptions) ? '' : Json::encode($this->clientOptions);
        $js = "jQuery('#$id').datepicker($clientOptions)";
        if ($value) {
            if (isset($this->clientOptions['multipleDatesSeparator']) && strpos($value, $this->clientOptions['multipleDatesSeparator'])) {
                $dates = array_map(function ($date) {
                    return strtotime($date) * 1000;
                }, explode($this->clientOptions['multipleDatesSeparator'], $value));
                $this->clientEvents = array_merge($this->clientEvents, ['selectDate' => '[new Date(' . $dates[0] . '), new Date(' . $dates[1] . ') ]']);
            } else {
                $this->clientEvents = array_merge($this->clientEvents, ['selectDate' => 'new Date(' . strtotime($value) * 1000 . ')']);
            }
        }
        foreach ($this->clientEvents as $event => $handler) {
            $js .= ".data('datepicker').$event($handler)";
        }
        $this->view->registerJs($js . ';');

        $this->options['value'] = $value;

        Html::addCssClass($this->options, [$this->addInputCss]);

        if (!empty($this->attribute1) && !empty($this->attribute2)) {
            $attribute1Tag = $this->hasModel() ? Html::activeTextInput($this->model, $this->attribute1, ['class' => ['hidden']]) : Html::textInput($this->name, $value, ['class' => ['hidden']]);

            $attribute1Id = Html::getInputId($this->model, $this->attribute1);
            $attribute2Id = Html::getInputId($this->model, $this->attribute2);

            $this->options = ArrayHelper::merge($this->options, [
                'attribute1id' => $attribute1Id,
                'attribute2id' => $attribute2Id,
            ]);

            $attribute2Tag = $this->hasModel() ? Html::activeTextInput($this->model, $this->attribute2, ['class' => ['hidden']]) : Html::textInput($this->name, $value, ['class' => ['hidden']]);
        } else {
            $attribute1Tag = '';
            $attribute2Tag = '';
        }

        if (!empty($this->name1) && !empty($this->name2)) {

            $attribute1Tag = $this->hasModel() ? Html::activeTextInput($this->model, $this->attribute1, ['class' => ['hidden']]) : Html::textInput($this->name1, $this->value1, [
                'class' => ['hidden'],
                'id' => $this->name1,
            ]);

            $attribute2Tag = $this->hasModel() ? Html::activeTextInput($this->model, $this->attribute2, ['class' => ['hidden']]) : Html::textInput($this->name2, $this->value2, [
                'class' => ['hidden'],
                'id' => $this->name2,
            ]);

            $attribute1Id = $this->name1;
            $attribute2Id = $this->name2;

            $this->options = ArrayHelper::merge($this->options, [
                'attribute1id' => $attribute1Id,
                'attribute2id' => $attribute2Id,
            ]);

        }

        return strtr($this->template, [
            '{input}' => $this->hasModel() ? Html::activeTextInput($this->model, $this->attribute, $this->options) . $attribute1Tag . $attribute2Tag : Html::textInput($this->name, $_GET[$this->name] ?? '', $this->options) . $attribute1Tag . $attribute2Tag,
        ]);

    }
}