<?php

namespace common\models;

/**
 * This is the model class for table "metodosentregas".
 *
 * @property int $id
 * @property string $descricao
 * @property string $diasEntrega
 * @property float $preco
 * @property int $vigor
 *
 * @property Fatura[] $faturas
 */
class Metodoentrega extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'metodosentregas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descricao', 'diasEntrega', 'preco', 'vigor'], 'required'],
            [['descricao', 'diasEntrega'], 'string'],
            [['preco'], 'number'],
            [['vigor'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'descricao' => 'Descricao',
            'diasEntrega' => 'Dias Entrega',
            'preco' => 'Preco',
            'vigor' => 'Vigor',
        ];
    }

    /**
     * Gets query for [[Faturas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFaturas()
    {
        return $this->hasMany(Fatura::class, ['metodoentrega_id' => 'id']);
    }
}
