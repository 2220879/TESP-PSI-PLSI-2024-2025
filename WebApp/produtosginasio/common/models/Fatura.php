<?php

namespace common\models;

/**
 * This is the model class for table "faturas".
 *
 * @property int $id
 * @property string $dataEmissao
 * @property string $horaEmissao
 * @property float $valorTotal
 * @property float $ivaTotal
 * @property int|null $nif
 * @property int $metodopagamento_id
 * @property int $metodoentrega_id
 * @property int $encomenda_id
 * @property int $profile_id
 *
 * @property Encomenda $encomenda
 * @property Linhafatura[] $linhasfaturas
 * @property Metodoentrega $metodoentrega
 * @property Metodopagamento $metodopagamento
 * @property Profile $profile
 */
class Fatura extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'faturas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dataEmissao', 'horaEmissao', 'valorTotal', 'ivaTotal', 'metodopagamento_id', 'metodoentrega_id', 'encomenda_id', 'profile_id'], 'required'],
            [['dataEmissao', 'horaEmissao'], 'safe'],
            [['valorTotal', 'ivaTotal'], 'number'],
            [['nif', 'metodopagamento_id', 'metodoentrega_id', 'encomenda_id', 'profile_id'], 'integer'],
            [['encomenda_id'], 'exist', 'skipOnError' => true, 'targetClass' => Encomenda::class, 'targetAttribute' => ['encomenda_id' => 'id']],
            [['metodoentrega_id'], 'exist', 'skipOnError' => true, 'targetClass' => Metodoentrega::class, 'targetAttribute' => ['metodoentrega_id' => 'id']],
            [['metodopagamento_id'], 'exist', 'skipOnError' => true, 'targetClass' => Metodopagamento::class, 'targetAttribute' => ['metodopagamento_id' => 'id']],
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
            'dataEmissao' => 'Data Emissao',
            'horaEmissao' => 'Hora Emissao',
            'valorTotal' => 'Valor Total',
            'ivaTotal' => 'Iva Total',
            'nif' => 'Nif',
            'metodopagamento_id' => 'Metodopagamento ID',
            'metodoentrega_id' => 'Metodoentrega ID',
            'encomenda_id' => 'Encomenda ID',
            'profile_id' => 'Profile ID',
        ];
    }

    /**
     * Gets query for [[Encomenda]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEncomenda()
    {
        return $this->hasOne(Encomenda::class, ['id' => 'encomenda_id']);
    }

    /**
     * Gets query for [[Linhasfaturas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLinhasfaturas()
    {
        return $this->hasMany(Linhafatura::class, ['fatura_id' => 'id']);
    }

    /**
     * Gets query for [[Metodoentrega]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMetodoentrega()
    {
        return $this->hasOne(Metodoentrega::class, ['id' => 'metodoentrega_id']);
    }

    /**
     * Gets query for [[Metodopagamento]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMetodopagamento()
    {
        return $this->hasOne(Metodopagamento::class, ['id' => 'metodopagamento_id']);
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
