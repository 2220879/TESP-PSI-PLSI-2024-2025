<?php

namespace common\models;

/**
 * This is the model class for table "metodospagamentos".
 *
 * @property int $id
 * @property string $metodoPagamento
 *
 * @property Fatura[] $faturas
 */
class Metodopagamento extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'metodospagamentos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['metodoPagamento'], 'required'],
            [['metodoPagamento'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'metodoPagamento' => 'Metodo Pagamento',
        ];
    }

    /**
     * Gets query for [[Faturas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFaturas()
    {
        return $this->hasMany(Fatura::class, ['metodopagamento_id' => 'id']);
    }
}
