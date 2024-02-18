<?php

/* @var $this yii\web\View */
/* @var $searchModel app\models\morningstar\search\MorningstarDataSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'MorningStar';
$this->params['breadcrumbs'][] = $this->title;

foreach ($data as $source => $items) {
    if (!empty($items)) {
        $sourceList[] = $source;
        foreach ($items as $i => $item) {
            $year = (int)date('Y', strtotime($item['date']));
            $month = (int)date('m', strtotime($item['date'])) - 1;
            $day = (int)date('d', strtotime($item['date']));
            $date = $year . '-' . $month . '-' . $day;
            $shares[$source][$item['fund_id']]['name'] = $item['name'];
            $shares[$source][$item['fund_id']]['data'][] = [$date, $item['current_shares']];
//            $shares[$source][$item['fund_id']]['data'][] = [intval(strtotime($item['date']) . '000'), $item['current_shares']];
        }
    }
}

?>
<div class="morningstar-data-index">
    <?php foreach ($sourceList as $source) : ?>
        <?php if (!empty($source)) : ?>
            <div class="card shadow-sm mt-3 mb-3">
                <div class="card-body">

                    <?= \miloschuman\highcharts\Highcharts::widget([
                        'options' => '{
                  "chart": {"type": "spline"},
                  "title": { "text": "' . $source . '" },
                  "navigator": {
                      "enabled": false
                  },
                  "xAxis": {
                    "type": "datetime",
                    "title": {
                        "text": "Дата"
                    }
                  },
                  "yAxis": {
                    "title": { "text": "Кол-во акций" }
                  },
                  "tooltip": {
                    "headerFormat": "<b>{series.name}</b><br>",
                    "pointFormat": "{point.x} : {point.y}"
                  },
                  
                  "data": { "dateFormat": "dd/mm/YYYY" },
                  "plotOptions": {
                    "series": {
                        "marker": {
                            "enabled": true,
                            "radius": 3
                        }
                    }
                  },
                  "series":' . json_encode(array_values($shares[$source])) . '
               }',
                    ]);
                    ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>
<!--"xAxis": {-->
<!--"type": "datetime",-->
<!--"title": {-->
<!--"text": "Date"-->
<!--}-->
<!--},-->
<!--"yAxis": {-->
<!--"title": { "text": "Fruit eaten" }-->
<!--},-->
<!--{-->
<!--"name": "Goldman Sachs GQG Ptnrs Intl Opps Instl",-->
<!--"data": [-->
<!--[1633031250000, 45851094],-->
<!--[1640893650000, 40851094]-->
<!--]-->
<!--}, {-->
<!--"name": "GQG Partners Global Equity-obsolete",-->
<!--"data": [-->
<!--[1638301650000, 4357031]-->
<!--] }, {-->
<!--"name": "Schroder ISF Glb Em Mkt Opps C Acc USD",-->
<!--"data": [-->
<!--[1637869650000, 3450325]-->
<!--] }-->