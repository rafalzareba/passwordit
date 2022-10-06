<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\search\CustomerSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Klienci';
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="ajaxCrudDatatable">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => require(__DIR__ . '/_columns.php'),
        'toolbar' => [
            'content' =>
                Html::a(Html::tag('i', null, ['class' => 'fas fa-edit me-2']) . 'Hurtowe zmiany', ['batch-update'], ['class' => 'btn btn-danger me-2', 'data-pjax' => 0]) .
                Html::a(Html::tag('i', null, ['class' => 'fas fa-plus me-2']) . 'Nowy Klient', ['create'], ['role' => 'modal-remote', 'title' => 'Nowy klient', 'class' => 'btn btn-primary'])
        ],
    ]) ?>
</div>
