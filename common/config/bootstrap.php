<?php
Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@backend', dirname(__DIR__, 2) . '/backend');
Yii::setAlias('@console', dirname(__DIR__, 2) . '/console');
Yii::setAlias('@tests', dirname(dirname(__DIR__)) . '/common/tests');