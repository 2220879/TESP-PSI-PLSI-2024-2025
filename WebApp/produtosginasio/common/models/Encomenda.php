<?php

namespace common\models;

/**
 * This is the model class for table "encomendas".
 *
 * @property int $id
 * @property string $data
 * @property string $hora
 * @property string $morada
 * @property int $telefone
 * @property string $email
 * @property string $estadoEncomenda
 * @property int $profile_id
 *
 * @property Fatura[] $faturas
 * @property Profile $profile
 */
class Encomenda extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'encomendas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['data', 'hora', 'morada', 'telefone', 'email', 'estadoEncomenda', 'profile_id'], 'required'],
            [['data', 'hora'], 'safe'],
            [['morada', 'email', 'estadoEncomenda'], 'string'],
            [['telefone', 'profile_id'], 'integer'],
            [['profile_id'], 'exist', 'skipOnError' => true, 'targetClass' => Profile::class, 'targetAttribute' => ['profile_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'data' => 'Data',
            'hora' => 'Hora',
            'morada' => 'Morada',
            'telefone' => 'Telefone',
            'email' => 'Email',
            'estadoEncomenda' => 'Estado Encomenda',
            'profile_id' => 'Profile ID',
        ];
    }

    /**
     * Gets query for [[Faturas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFaturas()
    {
        return $this->hasMany(Fatura::class, ['encomenda_id' => 'id']);
    }

    /**
     * Gets query for [[Profile]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::class, ['id' => 'profile_id']);
    }
}
