<?php

namespace backend\models;

/**
 * This is the model class for table "compras".
 *
 * @property int $id
 * @property float $total
 * @property string $dataDespesa
 * @property int $fornecedor_id
 *
 * @property Fornecedor $fornecedor
 * @property Linhacompra[] $linhascompras
 */
class Compra extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'compras';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['total', 'dataDespesa', 'fornecedor_id'], 'required'],
            [['total'], 'number'],
            [['dataDespesa'], 'safe'],
            [['fornecedor_id'], 'integer'],
            [['fornecedor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Fornecedor::class, 'targetAttribute' => ['fornecedor_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'total' => 'Total',
            'dataDespesa' => 'Data Despesa',
            'fornecedor_id' => 'Fornecedor ID',
        ];
    }

    /**
     * Gets query for [[Fornecedor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFornecedor()
    {
        return $this->hasOne(Fornecedor::class, ['id' => 'fornecedor_id']);
    }

    /**
     * Gets query for [[Linhascompras]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLinhascompras()
    {
        return $this->hasMany(Linhacompra::class, ['compra_id' => 'id']);
    }
}
