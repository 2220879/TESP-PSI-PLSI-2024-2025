<?php

namespace common\models;

use app\models\Fornecedore;

/**
 * This is the model class for table "marcas".
 *
 * @property int $id
 * @property string $nomeMarca
 *
 * @property Fornecedore[] $fornecedores
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
        return $this->hasMany(Fornecedore::class, ['marca_id' => 'id']);
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
