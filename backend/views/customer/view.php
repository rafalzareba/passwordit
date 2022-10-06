<?php

use backend\models\Customer;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\Customer $model */

?>
<div class="customer-view">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'created_at:datetime',
            'first_name',
            'last_name',
            [
                'attribute' => 'status',
                'value' => static function (Customer $model) {
                    return Customer::STATUS_LIST[$model->status];
                },
            ],
        ],
    ]) ?>
</div>
