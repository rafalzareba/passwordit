<?php

use backend\models\Customer;

return [
    [
        'created_at' => time(),
        'first_name' => 'Jan',
        'last_name' => 'Kowalski',
        'status' => Customer::STATUS_ACTIVE
    ],
    [
        'created_at' => time(),
        'first_name' => 'Gustaw',
        'last_name' => 'Wójcik',
        'status' => Customer::STATUS_ACTIVE
    ],
    [
        'created_at' => time(),
        'first_name' => 'Ryszard',
        'last_name' => 'Wróbel',
        'status' => Customer::STATUS_INACTIVE
    ],
    [
        'created_at' => time(),
        'first_name' => 'Bartosz',
        'last_name' => 'Mazur',
        'status' => Customer::STATUS_ACTIVE
    ],
    [
        'created_at' => time(),
        'first_name' => 'Bogdan',
        'last_name' => 'Jankowski',
        'status' => Customer::STATUS_INACTIVE
    ],

];