<?php

namespace backend\models;

use kartik\builder\BaseForm;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "customer".
 *
 * @property int $id
 * @property int $created_at
 * @property string $first_name
 * @property string $last_name
 * @property string $status
 */
class Customer extends ActiveRecord
{

    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';
    public const STATUS_LIST = [
        self::STATUS_ACTIVE => 'Aktywny',
        self::STATUS_INACTIVE => 'Nieaktywny',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customer';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'updatedAtAttribute' => false,
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name', 'status'], 'required'],
            [['created_at'], 'integer'],
            [['status'], 'string'],
            [['first_name', 'last_name'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Data dodania',
            'first_name' => 'Imię',
            'last_name' => 'Nazwisko',
            'status' => 'Status',
        ];
    }

    /**
     * List of attributes for batch update form
     * @return array[]
     */
    public function getFormAttributes()
    {
        return [
            'id' => [
                'type' => BaseForm::INPUT_HIDDEN,
                'columnOptions' => ['hidden' => true]
            ],
            'first_name' => ['type' => BaseForm::INPUT_TEXT],
            'last_name' => ['type' => BaseForm::INPUT_TEXT],
            'created_at' => ['type' => BaseForm::INPUT_STATIC, 'format' => 'datetime'],
            'status' => [
                'type' => BaseForm::INPUT_DROPDOWN_LIST,
                'items' => self::STATUS_LIST,
            ],
        ];
    }

}
