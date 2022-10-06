<?php

namespace common\fixtures;

use yii\test\ActiveFixture;
use backend\models\Customer;

class CustomerFixture extends ActiveFixture
{
    public $modelClass = Customer::class;
    public $dataFile = '@tests/_data/customer.php';
}