<?php

namespace backend\tests\unit;

use common\models\Encomenda;
use common\models\Fatura;
use common\models\Linhafatura;
use common\models\Metodoentrega;
use common\models\Metodopagamento;
use common\models\Produto;
use common\models\Profile;
use Yii;


class FaturaTest extends \Codeception\Test\Unit
{

    //TESTE FATURA
    public function testFaturaValida()
    {
        $fatura = new Fatura();
        $fatura->dataEmissao = date('Y-m-d');
        $fatura->horaEmissao = date('H:i:s');
        $fatura->valorTotal = 55.00;
        $fatura->ivaTotal = 23.00;
        $fatura->nif = 123456789;
        $fatura->metodopagamento_id = Metodopagamento::find()->one()->id;
        $fatura->metodoentrega_id = Metodoentrega::find()->one()->id;
        $fatura->encomenda_id = Encomenda::find()->one()->id;
        $fatura->profile_id = Profile::find()->one()->id;

        $this->assertTrue($fatura->save());
    }
    public function testFaturaSemCampos()
    {
        $fatura = new Fatura();
        $fatura->dataEmissao = '';
        $fatura->horaEmissao = '';
        $fatura->valorTotal = null;
        $fatura->ivaTotal = null;
        $fatura->nif = null;
        $fatura->metodopagamento_id = null;
        $fatura->metodoentrega_id = null;
        $fatura->encomenda_id = null;
        $fatura->profile_id = null;

        $this->assertFalse($fatura->save(), 'A fatura não deveria ser guardada sem campos obrigatórios.');
    }

    public function testFaturaApagar()
    {
        $fatura = Fatura::find()->one();
        $this->assertNotNull($fatura);

        $this->assertTrue($fatura->delete() !== false, 'A fatura deveria ter sido apagada com sucesso.');
        $this->assertNull(Fatura::findOne($fatura->id));
    }


    //TESTE LINHAFATURA
    public function testLinhaFaturaValida()
    {
        $linha = new Linhafatura();
        $linha->dataVenda = date('Y-m-d');
        $linha->nomeProduto = 'Produto';
        $linha->quantidade = 5;
        $linha->precoUnit = 20.00;
        $linha->valorIva = 23.00;
        $linha->valorComIva = 123.00;
        $linha->subtotal = 100.00;
        $linha->fatura_id = Fatura::find()->one()->id;
        $linha->produto_id = Produto::find()->one()->id;

        $this->assertTrue($linha->save());
    }

    public function testLinhaFaturaSemCampos()
    {
        $linha = new Linhafatura();
        $linha->dataVenda = date('Y-m-d');
        $linha->nomeProduto = 'Produto incompleto';
        $linha->quantidade = null;
        $linha->precoUnit = null;
        $linha->valorIva = null;
        $linha->valorComIva = null;
        $linha->subtotal =null;
        $linha->fatura_id =null;
        $linha->produto_id = null;

        $this->assertFalse($linha->save(), 'A linha de fatura não deveria ser guardada sem campos obrigatórios.');
    }

    public function testLinhaFaturaApagar()
    {
        $linha = Linhafatura::find()->one();
        $this->assertNotNull($linha, 'Nenhuma linha de fatura encontrada para apagar.');

        $this->assertTrue($linha->delete() !== false, 'A linha de fatura deveria ter sido apagada com sucesso.');
        $this->assertNull(Linhafatura::findOne($linha->id));
    }
}


