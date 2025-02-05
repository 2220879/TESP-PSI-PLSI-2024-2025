<?php

namespace frontend\tests\unit;

use common\models\Avaliacao;
use common\models\Produto;
use common\models\Profile;

class AvaliacaoTest extends \Codeception\Test\Unit
{
    // tests
    public function testSaveAvaliacaoNulaOuVazia()
    {
        $avaliacao = new Avaliacao();

        $profile = Profile::find()->where(['id' => 1])->one();

        $produto = Produto::find()->where(['id' => 8])->one();

        $avaliacao->descricao = "";
        $avaliacao->produto_id = $produto->id;
        $avaliacao->profile_id = $profile->id;
        $this->assertFalse($avaliacao->save());
    }

    public function testSaveAvaliacao()
    {
        $avaliacao = new Avaliacao();

        $profile = Profile::find()->where(['id' => 2])->one();

        $produto = Produto::find()->where(['id' => 8])->one();

        $avaliacao->descricao = "Muito bom";
        $avaliacao->produto_id = $produto->id;
        $avaliacao->profile_id = $profile->id;
        $avaliacao->save();
        $this->assertTrue($avaliacao->validate());
    }

    public function testUpdateAvaliacao()
    {
        $avaliacao = Avaliacao::find()->where(['id' => 1])->one();

        $profile = Profile::find()->where(['id' => 2])->one();

        $produto = Produto::find()->where(['id' => 8])->one();

        $avaliacao->descricao = "Bom";
        $avaliacao->produto_id = $produto->id;
        $avaliacao->profile_id = $profile->id;
        $avaliacao->save();

        $this->assertTrue($avaliacao->validate());
    }

    public function testRemoveAvaliacao()
    {
        $avaliacao = Avaliacao::find()->where(['id' => 1])->one();

        $this->assertGreaterThan(0, $avaliacao->delete());
    }
}
