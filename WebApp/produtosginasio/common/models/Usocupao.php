<?php

namespace common\models;

/**
 * This is the model class for table "usocupoes".
 *
 * @property int $id
 * @property int $cupaodesconto_id
 * @property int $profile_id
 * @property string $dataUso
 *
 * @property Cupaodesconto $cupaodesconto
 * @property Profile $profile
 */
class Usocupao extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'usocupoes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cupaodesconto_id', 'profile_id', 'dataUso'], 'required'],
            [['cupaodesconto_id', 'profile_id'], 'integer'],
            [['dataUso'], 'safe'],
            [['cupaodesconto_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cupaodesconto::class, 'targetAttribute' => ['cupaodesconto_id' => 'id']],
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
            'cupaodesconto_id' => 'Cupaodesconto ID',
            'profile_id' => 'Profile ID',
            'dataUso' => 'Data Uso',
        ];
    }

    /**
     * Gets query for [[Cupaodesconto]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCupaodesconto()
    {
        return $this->hasOne(Cupaodesconto::class, ['id' => 'cupaodesconto_id']);
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
