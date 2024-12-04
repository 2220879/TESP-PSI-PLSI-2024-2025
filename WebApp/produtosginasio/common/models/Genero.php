<?php

namespace common\models;

/**
 * This is the model class for table "generos".
 *
 * @property int $id
 * @property string $referencia
 *
 * @property Produto[] $produtos
 */
class Genero extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'generos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['referencia'], 'required'],
            [['referencia'], 'unique','message'=>'Este genero ja esta criado'],
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
        return $this->hasMany(Produto::class, ['genero_id' => 'id']);
    }
}
