<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "BPLOG".
 *
 * @property integer $ID
 * @property string $VIPKO
 * @property string $VIPTYPE
 * @property string $VBGROUP
 * @property string $VBID
 * @property string $EXPDATE
 * @property string $TXDATE
 * @property string $LOCKO
 * @property string $STDNO
 * @property string $MEMONO
 * @property string $MEMTYPE
 * @property string $BP
 * @property string $REFAMT
 */
class BPLOG extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'BPLOG';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['VIPKO', 'VIPTYPE', 'VBGROUP', 'VBID', 'TXDATE', 'BP', 'REFAMT'], 'required'],
            [['EXPDATE', 'TXDATE'], 'safe'],
            [['BP', 'REFAMT'], 'number'],
            [['VIPKO'], 'string', 'max' => 20],
            [['VIPTYPE', 'VBGROUP', 'VBID', 'LOCKO', 'MEMONO'], 'string', 'max' => 10],
            [['STDNO', 'MEMTYPE'], 'string', 'max' => 2],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'VIPKO' => 'Vipko',
            'VIPTYPE' => 'Viptype',
            'VBGROUP' => 'Vbgroup',
            'VBID' => 'Vbid',
            'EXPDATE' => 'Expdate',
            'TXDATE' => 'Txdate',
            'LOCKO' => 'Locko',
            'STDNO' => 'Stdno',
            'MEMONO' => 'Memono',
            'MEMTYPE' => 'Memtype',
            'BP' => 'Bp',
            'REFAMT' => 'Refamt',
        ];
    }
}
