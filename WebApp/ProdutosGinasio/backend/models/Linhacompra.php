<?php

namespace app\models;

use common\models\Produto;

/**
 * This is the model class for table "linhascompras".
 *
 * @property int $id
 * @property int $quantidade
 * @property float $preco
 * @property float $iva
 * @property int $compra_id
 * @property int $produto_id
 *
 * @property Compra $compra
 * @property Produto $produto
 */
class Linhacompra extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'linhascompras';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['quantidade', 'preco', 'iva', 'compra_id', 'produto_id'], 'required'],
            [['quantidade', 'compra_id', 'produto_id'], 'integer'],
            [['preco', 'iva'], 'number'],
            [['compra_id'], 'exist', 'skipOnError' => true, 'targetClass' => Compra::class, 'targetAttribute' => ['compra_id' => 'id']],
            [['produto_id'], 'exist', 'skipOnError' => true, 'targetClass' => Produto::class, 'targetAttribute' => ['produto_id' => 'id']],
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
            'preco' => 'Preco',
            'iva' => 'Iva',
            'compra_id' => 'Compra ID',
            'produto_id' => 'Produto ID',
        ];
    }

    /**
     * Gets query for [[Compra]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompra()
    {
        return $this->hasOne(Compra::class, ['id' => 'compra_id']);
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
}
