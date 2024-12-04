<?php

namespace common\models;

/**
 * This is the model class for table "tamanhos".
 *
 * @property int $id
 * @property string $referencia
 *
 * @property Produto[] $produtos
 */
class Tamanho extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tamanhos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['referencia'], 'required'],
            [['referencia'], 'unique','message'=>'Este tamanho ja existe'],
            [['referencia'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'referencia' => 'Referencia',
        ];
    }

    /**
     * Gets query for [[Produtos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProdutos()
    {
        return $this->hasMany(Produto::class, ['tamanho_id' => 'id']);
    }
}
