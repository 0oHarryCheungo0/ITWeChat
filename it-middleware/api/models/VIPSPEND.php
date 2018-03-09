<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "VIPSPEND".
 *
 * @property integer $id
 * @property string $VIPKO
 * @property string $VIPTYPE
 * @property string $TXDATE
 * @property string $LOCKO
 * @property string $STDNO
 * @property string $MEMONO
 * @property string $MEMTYPE
 * @property string $QTY
 * @property string $DTRAMT
 */
class VIPSPEND extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'VIPSPEND';
    }

    public function getShop(){
        return $this->hasOne(LOCATION::className(),['LOCKO'=>'LOCKO']);
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['TXDATE'], 'safe'],
            [['QTY', 'DTRAMT'], 'number'],
            [['VIPKO'], 'string', 'max' => 20],
            [['VIPTYPE', 'LOCKO'], 'string', 'max' => 10],
            [['STDNO', 'MEMTYPE'], 'string', 'max' => 2],
            [['MEMONO'], 'string', 'max' => 7],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'      => 'ID',
            'VIPKO'   => 'Vipko',
            'VIPTYPE' => 'Viptype',
            'TXDATE'  => 'Txdate',
            'LOCKO'   => 'Locko',
            'STDNO'   => 'Stdno',
            'MEMONO'  => 'Memono',
            'MEMTYPE' => 'Memtype',
            'QTY'     => 'Qty',
            'DTRAMT'  => 'Dtramt',
        ];
    }
}
