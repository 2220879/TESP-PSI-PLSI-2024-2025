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
        $fatura->ivaTotal = 0.23;
        $fatura->nif = 123456789;
        $fatura->metodopagamento_id = 1;
        $fatura->metodoentrega_id = 1;
        $fatura->encomenda_id = 2;
        $fatura->profile_id = 2;

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
        $fatura = Fatura::find()->where(['id' => 1])->one();
        $this->assertNotNull($fatura);

        $this->assertTrue($fatura->delete() !== false, 'A fatura deveria ter sido apagada com sucesso.');
        $this->assertNull(Fatura::findOne($fatura->id));
    }


    //TESTE LINHAFATURA
    public function testLinhaFaturaValida()
    {
        $linha = new Linhafatura();
        $linha->dataVenda = date('Y-m-d');
        $linha->nomeProduto = 'Calção Adida';
        $linha->quantidade = 1;
        $linha->precoUnit = 20.00;
        $linha->valorIva = 0.23;
        $linha->valorComIva = 24.60;
        $linha->subtotal = 24.60;
        $linha->fatura_id = 2;
        $linha->produto_id = 8;

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
        $linha->subtotal = null;
        $linha->fatura_id = null;
        $linha->produto_id = null;

        $this->assertFalse($linha->save(), 'A linha de fatura não deveria ser guardada sem campos obrigatórios.');
    }

    public function testLinhaFaturaApagar()
    {
        $linha = Linhafatura::find()->where(['id' => 1])->one();
        $this->assertNotNull($linha, 'Nenhuma linha de fatura encontrada para apagar.');

        $this->assertTrue($linha->delete() !== false, 'A linha de fatura deveria ter sido apagada com sucesso.');
        $this->assertNull(Linhafatura::findOne($linha->id));
    }
}


