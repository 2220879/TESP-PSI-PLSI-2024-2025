<?php

namespace common\models;

/**
 * This is the model class for table "linhasfaturas".
 *
 * @property int $id
 * @property string $dataVenda
 * @property string $nomeProduto
 * @property int $quantidade
 * @property float $precoUnit
 * @property float $valorIva
 * @property float $valorComIva
 * @property float $subtotal
 * @property int $fatura_id
 * @property int $produto_id
 *
 * @property Fatura $fatura
 * @property Produto $produto
 */
class Linhafatura extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'linhasfaturas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dataVenda', 'nomeProduto', 'quantidade', 'precoUnit', 'valorIva', 'valorComIva', 'subtotal', 'fatura_id'], 'required'],
            [['dataVenda'], 'safe'],
            [['quantidade', 'fatura_id'], 'integer'],
            [['precoUnit', 'valorIva', 'valorComIva', 'subtotal'], 'number'],
            [['nomeProduto'], 'string', 'max' => 50],
            [['fatura_id'], 'exist', 'skipOnError' => true, 'targetClass' => Fatura::class, 'targetAttribute' => ['fatura_id' => 'id']],
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
            'dataVenda' => 'Data Venda',
            'nomeProduto' => 'Nome Produto',
            'quantidade' => 'Quantidade',
            'precoUnit' => 'Preco Unit',
            'valorIva' => 'Valor Iva',
            'valorComIva' => 'Valor Com Iva',
            'subtotal' => 'Subtotal',
            'fatura_id' => 'Fatura ID',
            'produto_id' => 'Produto ID',
        ];
    }

    /**
     * Gets query for [[Fatura]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFatura()
    {
        return $this->hasOne(Fatura::class, ['id' => 'fatura_id']);
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
