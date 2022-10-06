<?php

use kartik\daterange\DateRangePicker;
use kartik\grid\GridView;

$container = Yii::$container;

$container->set(GridView::class, [
    'id' => 'crud-datatable',
    'pjax' => true,
    'condensed' => true,
    'responsiveWrap' => false,
    'resizableColumns' => false,
    'hideResizeMobile' => false,
    'bordered' => false,
    'panel' => [
        'heading' => false,
        'before' => '',
        'after' => false,
    ],
    'panelHeadingTemplate' => '',
    'panelFooterTemplate' => '
{summary}
<div class="d-block align-items-center justify-content-center justify-content-md-end">
        {pager}
    </div>
    {footer}
    ',
]);


$container->set(DateRangePicker::class, [
    'presetDropdown' => true,
    'convertFormat' => true,
    'pluginOptions' => [
        'timePicker' => false,
        'locale' => [
            'format' => 'Y-m-d'
        ],
    ],
    'containerTemplate' => <<< HTML
        <div class="kv-drp-dropdown position-relative">
            <input type="text" readonly class="form-control range-value bg-white" value="{value}">
             {input}
            <span class="right-ind kv-clear cursor-pointer" style="" title="Wyczyść">&times;</span>
        </div>
HTML
]);


