<?php

use backend\models\Customer;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\Customer $model */
/** @var yii\bootstrap5\ActiveForm $form */
?>

<div class="customer-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList(Customer::STATUS_LIST, ['prompt' => '']) ?>

    <?php ActiveForm::end(); ?>
</div>
