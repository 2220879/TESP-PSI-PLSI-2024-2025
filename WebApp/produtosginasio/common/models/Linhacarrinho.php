<?php

namespace common\models;

/**
 * This is the model class for table "linhascarrinhos".
 *
 * @property int $id
 * @property int $quantidade
 * @property float $precoUnit
 * @property float $valorIva
 * @property float $valorComIva
 * @property float $subtotal
 * @property int $carrinhocompras_id
 * @property int $produto_id
 * @property int|null $tamanho_id
 *
 * @property Carrinhocompra $carrinhocompras
 * @property Produto $produto
 * @property Tamanho $tamanho
 */
class Linhacarrinho extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'linhascarrinhos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['quantidade', 'precoUnit', 'valorIva', 'valorComIva', 'subtotal', 'carrinhocompras_id', 'produto_id'], 'required'],
            [['quantidade', 'carrinhocompras_id', 'produto_id'], 'integer'],
            [['precoUnit', 'valorIva', 'valorComIva', 'subtotal'], 'number'],
            [['carrinhocompras_id'], 'exist', 'skipOnError' => true, 'targetClass' => Carrinhocompra::class, 'targetAttribute' => ['carrinhocompras_id' => 'id']],
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
            'id' => 'ID',
            'quantidade' => 'Quantidade',
            'precoUnit' => 'Preco Unit',
            'valorIva' => 'Valor Iva',
            'valorComIva' => 'Valor Com Iva',
            'subtotal' => 'Subtotal',
            'carrinhocompras_id' => 'Carrinhocompras ID',
            'produto_id' => 'Produto ID',
            'tamanho_id' => 'Tamanho ID',
        ];
    }

    /**
     * Gets query for [[Carrinhocompras]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCarrinhocompras()
    {
        return $this->hasOne(Carrinhocompra::class, ['id' => 'carrinhocompras_id']);
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
