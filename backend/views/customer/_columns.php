<?php

use backend\models\Customer;
use kartik\grid\ActionColumn;
use kartik\grid\DataColumn;
use kartik\grid\GridViewInterface;
use yii\bootstrap5\Html;
use yii\helpers\Url;

return [

    [
        'attribute' => 'id',
        'width' => '100px',
        'format' => 'raw',
    ],
    [
        'class' => DataColumn::class,
        'attribute' => 'first_name',
    ],
    [
        'class' => DataColumn::class,
        'attribute' => 'last_name',
    ],
    [
        'class' => DataColumn::class,
        'attribute' => 'created_at',
        'contentOptions' => ['class' => 'text-center text-nowrap'],
        'format' => 'datetime',
        'filterType' => GridViewInterface::FILTER_DATE_RANGE,
        'filterWidgetOptions' => [
            'convertFormat' => true,
            'presetDropdown' => true,
            'pluginOptions' => [
                'timePicker' => false,
                'locale' => [
                    'format' => 'Y-m-d H:i'
                ],
                'opens' => 'left'
            ]
        ],
    ],
    [
        'class' => DataColumn::class,
        'attribute' => 'status',
        'value' => static function (Customer $model) {
            return Customer::STATUS_LIST[$model->status];
        },
        'filter' => Customer::STATUS_LIST,
    ],
    [
        'class' => ActionColumn::class,
        'urlCreator' => static function ($action, Customer $model, $key) {
            return Url::to([$action, 'id' => $key]);
        },
        'buttons' => [
            'view' => static function ($url, Customer $model) {
                return Html::a(Html::tag('i', null, ['class' => 'fas fa-eye']), ['view', 'id' => $model->id], [
                    'role' => 'modal-remote'
                ]);
            },
            'update' => static function ($url, Customer $model) {
                return Html::a(Html::tag('i', null, ['class' => 'fas fa-pencil-alt']), ['update', 'id' => $model->id], [
                    'role' => 'modal-remote'
                ]);
            },
            'delete' => static function ($url, Customer $model) {
                return Html::a(Html::tag('i', null, ['class' => 'fas fa-trash-alt']), ['delete', 'id' => $model->id], [
                    'role' => 'modal-remote',
                    'data-confirm' => false,
                    'data-method' => false,
                    'data-request-method' => 'post',
                    'data-confirm-title' => 'Jesteś pewny?',
                    'data-confirm-message' => 'Czy na pewno usunąć Klienta?',
                ]);
            },
        ],
    ],
];