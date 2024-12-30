<?php

namespace common\models;

use backend\models\Linhacompra;

/**
 * This is the model class for table "produtos".
 *
 * @property int $id
 * @property string $nomeProduto
 * @property float $preco
 * @property int $quantidade
 * @property string $descricaoProduto
 * @property int $marca_id
 * @property int $categoria_id
 * @property int $iva_id
 * @property int|null $genero_id
 *
 * @property Avaliacao[] $avaliacos
 * @property Categoria $categoria
 * @property Favorito[] $favoritos
 * @property Genero $genero
 * @property Imagem[] $imagens
 * @property Iva $iva
 * @property Linhacarrinho[] $linhascarrinhos
 * @property Linhacompra[] $linhascompras
 * @property Marca $marca
 * @property ProdutosHasTamanho[] $produtosHasTamanhos
 * @property Tamanho[] $tamanhos
 */
class Produto extends \yii\db\ActiveRecord
{
    const SCENARIO_CREATE = 'create';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'produtos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nomeProduto', 'preco', 'descricaoProduto', 'marca_id', 'categoria_id', 'iva_id'], 'required'],
            [['preco'], 'number'],
            [['quantidade', 'marca_id', 'categoria_id', 'iva_id', 'genero_id'], 'integer'],
            [['descricaoProduto'], 'string'],
            [['nomeProduto'], 'string', 'max' => 50],
            [['quantidade'], 'integer', 'min' => 0, 'message' => 'A quantidade do stock não pode ser negativa.'],
            [['preco'], 'number', 'min' => 0.01, 'message' => 'O preço deve ser maior que zero.'],
            [['categoria_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categoria::class, 'targetAttribute' => ['categoria_id' => 'id']],
            [['genero_id'], 'exist', 'skipOnError' => true, 'targetClass' => Genero::class, 'targetAttribute' => ['genero_id' => 'id']],
            [['iva_id'], 'exist', 'skipOnError' => true, 'targetClass' => Iva::class, 'targetAttribute' => ['iva_id' => 'id']],
            [['marca_id'], 'exist', 'skipOnError' => true, 'targetClass' => Marca::class, 'targetAttribute' => ['marca_id' => 'id']],
            ['quantidade', 'default', 'value' => 0, 'on' => self::SCENARIO_CREATE],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nomeProduto' => 'Nome Produto',
            'preco' => 'Preco',
            'quantidade' => 'Quantidade',
            'descricaoProduto' => 'Descricao Produto',
            'marca_id' => 'Marca ID',
            'categoria_id' => 'Categoria ID',
            'iva_id' => 'Iva ID',
            'genero_id' => 'Genero ID',
        ];
    }

    /**
     * Gets query for [[Avaliacos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAvaliacos()
    {
        return $this->hasMany(Avaliacao::class, ['produto_id' => 'id']);
    }

    /**
     * Gets query for [[Categoria]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategoria()
    {
        return $this->hasOne(Categoria::class, ['id' => 'categoria_id']);
    }

    /**
     * Gets query for [[Favoritos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFavoritos()
    {
        return $this->hasMany(Favorito::class, ['produto_id' => 'id']);
    }

    /**
     * Gets query for [[Genero]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGenero()
    {
        return $this->hasOne(Genero::class, ['id' => 'genero_id']);
    }

    /**
     * Gets query for [[Imagens]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getImagens()
    {
        return $this->hasMany(Imagem::class, ['produto_id' => 'id']);
    }

    /**
     * Gets query for [[Iva]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIva()
    {
        return $this->hasOne(Iva::class, ['id' => 'iva_id']);
    }

    /**
     * Gets query for [[Linhascarrinhos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLinhascarrinhos()
    {
        return $this->hasMany(Linhacarrinho::class, ['produto_id' => 'id']);
    }

    /**
     * Gets query for [[Linhascompras]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLinhascompras()
    {
        return $this->hasMany(Linhacompra::class, ['produto_id' => 'id']);
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

    /**
     * Gets query for [[ProdutosHasTamanhos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProdutosHasTamanhos()
    {
        return $this->hasMany(ProdutosHasTamanho::class, ['produto_id' => 'id']);
    }

    /**
     * Gets query for [[Tamanhos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTamanhos()
    {
        return $this->hasMany(Tamanho::class, ['id' => 'tamanho_id'])->viaTable('produtos_has_tamanhos', ['produto_id' => 'id']);
    }
}
