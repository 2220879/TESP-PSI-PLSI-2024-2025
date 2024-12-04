<?php

namespace common\models;

use backend\models\Fornecedor;

/**
 * This is the model class for table "marcas".
 *
 * @property int $id
 * @property string $nomeMarca
 *
 * @property Fornecedor[] $fornecedores
 * @property Produto[] $produtos
 */
class Marca extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'marcas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nomeMarca'], 'required'],
            [['nomeMarca'], 'unique','message'=>'Esta marca ja esta criada'],
            [['nomeMarca'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nomeMarca' => 'Nome Marca',
        ];
    }

    /**
     * Gets query for [[Fornecedores]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFornecedores()
    {
        return $this->hasMany(Fornecedor::class, ['marca_id' => 'id']);
    }

    /**
     * Gets query for [[Produtos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProdutos()
    {
        return $this->hasMany(Produto::class, ['marca_id' => 'id']);
    }
}
