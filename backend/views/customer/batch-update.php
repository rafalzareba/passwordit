<?php

use kartik\form\ActiveForm;
use kartik\builder\TabularForm;
use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var backend\models\search\CustomerSearch $model */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Hurtowe zmiany';
$this->params['breadcrumbs'][] = ['label' => 'Klienci', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$form = ActiveForm::begin();

echo TabularForm::widget([
    'dataProvider' => $dataProvider,
    'form' => $form,
    'attributes' => $model->getFormAttributes(),
    'actionColumn' => [
        'width' => '60px',
        'updateOptions' => ['style' => 'display:none'],
        'viewOptions' => [
            'role' => 'modal-remote'
        ],
        'deleteOptions' => [
            'role' => 'modal-remote',
            'data-confirm' => false,
            'data-method' => false,// for overide yii data api
            'data-request-method' => 'post',
            'data-confirm-title' => 'Jesteś pewny?',
            'data-confirm-message' => 'Czy na pewno usunąć Klienta?',
        ],
    ],
    'gridSettings' => [
        'id' => 'crud-datatable',
        'pjax' => true,
        'condensed' => true,
        'floatHeader' => true,
        'toolbar' => [
            'content' =>
                Html::a(Html::tag('i', null, ['class' => 'fas fa-plus me-2']) . 'Nowy Klient', ['create'], ['role' => 'modal-remote', 'title' => 'Nowy klient', 'class' => 'btn btn-primary'])
        ],
        'panel' => [
            'heading' => false,
            'after' =>
                Html::a(Html::tag('i', null, ['class' => 'fas fa-trash-alt me-2']) . 'Usuń', 'batch-delete', [
                    'data-confirm' => 'Czy na pewno usunąć wybrane/widoczne na stronie rekordy?',
                    'data-method' => 'post',
                    'class' => 'btn btn-danger'
                ]) . ' ' .
                Html::submitButton(Html::tag('i', null, ['class' => 'fas fa-save me-2']) . 'Zapisz', ['class' => 'btn btn-primary']) . Html::tag('p', 'Usuń/zapisz zaznaczone lub wszystkie widoczne na stronie.')
        ]
    ]
]);
ActiveForm::end();