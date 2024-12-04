<?php

namespace backend\models;

use common\models\Marca;

/**
 * This is the model class for table "fornecedores".
 *
 * @property int $id
 * @property string $nome
 * @property int $telefone
 * @property string $email
 * @property int $marca_id
 *
 * @property Compra[] $compras
 * @property Marca $marca
 */
class Fornecedor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fornecedores';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nome', 'telefone', 'email', 'marca_id'], 'required'],
            [['nome'], 'string'],
            [['telefone', 'marca_id'], 'integer'],
            [['email'], 'string', 'max' => 50],
            [['marca_id'], 'exist', 'skipOnError' => true, 'targetClass' => Marca::class, 'targetAttribute' => ['marca_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nome' => 'Nome',
            'telefone' => 'Telefone',
            'email' => 'Email',
            'marca_id' => 'Marca ID',
        ];
    }

    /**
     * Gets query for [[Compras]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompras()
    {
        return $this->hasMany(Compra::class, ['fornecedor_id' => 'id']);
    }

    /**
     * Gets query for [[Marca]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMarca()
    {
        return $this->hasOne(Marca::class, ['id' => 'marca_id']);
    }
}
