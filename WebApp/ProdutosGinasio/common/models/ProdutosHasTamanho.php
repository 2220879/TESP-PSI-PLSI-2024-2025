<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "produtos_has_tamanhos".
 *
 * @property int $produto_id
 * @property int $tamanho_id
 * @property int $quantidade
 *
 * @property Produto $produto
 * @property Tamanho $tamanho
 */
class ProdutosHasTamanho extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'produtos_has_tamanhos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['produto_id', 'tamanho_id', 'quantidade'], 'required'],
            [['produto_id', 'tamanho_id', 'quantidade'], 'integer'],
            [['produto_id', 'tamanho_id'], 'unique', 'targetAttribute' => ['produto_id', 'tamanho_id']],
            [['produto_id'], 'exist', 'skipOnError' => true, 'targetClass' => Produto::class, 'targetAttribute' => ['produto_id' => 'id']],
            [['tamanho_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tamanho::class, 'targetAttribute' => ['tamanho_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'produto_id' => 'Produto ID',
            'tamanho_id' => 'Tamanho ID',
            'quantidade' => 'Quantidade',
        ];
    }

    /**
     * Gets query for [[Produto]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduto()
    {
        return $this->hasOne(Produto::class, ['id' => 'produto_id']);
    }

    /**
     * Gets query for [[Tamanho]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTamanho()
    {
        return $this->hasOne(Tamanho::class, ['id' => 'tamanho_id']);
    }
}
