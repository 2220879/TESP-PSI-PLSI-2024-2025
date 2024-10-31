<?php

namespace common\models;

use app\models\Usocupo;

/**
 * This is the model class for table "cupoesdescontos".
 *
 * @property int $id
 * @property string $codigo
 * @property float $desconto
 * @property string $dataFim
 *
 * @property Usocupo[] $usocupos
 */
class Cupaodesconto extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cupoesdescontos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigo', 'desconto', 'dataFim'], 'required'],
            [['desconto'], 'number'],
            [['dataFim'], 'safe'],
            [['codigo'], 'string', 'max' => 50],
            [['codigo'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'codigo' => 'Codigo',
            'desconto' => 'Desconto',
            'dataFim' => 'Data Fim',
        ];
    }

    /**
     * Gets query for [[Usocupos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsocupos()
    {
        return $this->hasMany(Usocupo::class, ['cupaodesconto_id' => 'id']);
    }
}
