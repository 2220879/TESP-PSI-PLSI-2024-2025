<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "compras".
 *
 * @property int $id
 * @property float $total
 * @property string $dataDespesa
 * @property int $fornecedor_id
 *
 * @property Fornecedore $fornecedor
 * @property Linhascompra[] $linhascompras
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
            [['fornecedor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Fornecedore::class, 'targetAttribute' => ['fornecedor_id' => 'id']],
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
        return $this->hasOne(Fornecedore::class, ['id' => 'fornecedor_id']);
    }

    /**
     * Gets query for [[Linhascompras]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLinhascompras()
    {
        return $this->hasMany(Linhascompra::class, ['compra_id' => 'id']);
    }
}
