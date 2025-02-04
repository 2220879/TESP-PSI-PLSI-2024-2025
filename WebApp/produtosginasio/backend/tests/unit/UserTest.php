<?php


namespace backend\tests\unit;

use backend\tests\UnitTester;
use common\models\Avaliacao;
use common\models\Carrinhocompra;
use common\models\Encomenda;
use common\models\Fatura;
use common\models\Favorito;
use common\models\Linhacarrinho;
use common\models\Linhafatura;
use common\models\Profile;
use common\models\User;
use common\models\Usocupao;

class UserTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
    }

    // tests
    public function testCriarUtilizador()
    {
        $username = 'Pedro Francisco';
        $password = 'Admin*1234567';
        $email = 'pedrofrancisco@gmail.com';
        $nif = 123456789;
        $morada = 'Leiria';
        $telefone = 912345678;

        $user = new User();

        if (User::find()->where(['username' => $username])->exists()) {
            $this->fail('O username já está existe ' . $username);
        } else if (User::find()->where(['email' => $email])->exists()) {
            $this->fail('O email inserido já está associado a outro Utilizador ' . $email);
        } else if (Profile::find()->where(['nif' => $nif])->exists()) {
            $this->fail('O nif inserido já está associado a outro Utilizador ' . $nif);
        } else if (Profile::find()->where(['telefone' => $telefone])->exists()) {
            $this->fail('O telefone inserido já está associado a outro Utilizador ' . $telefone);
        } else {
            $user->username = $username;
            $user->setPassword($password);
            $user->generateAuthKey();
            $user->email = $email;
            $this->assertTrue($user->save());

            $profile = new Profile();
            $profile->nif = $nif;
            $profile->morada = $morada;
            $profile->telefone = $telefone;
            $profile->user_id = $user->id;
            $this->assertTrue($profile->save());
        }
    }

    public function testEditarUtilizadorProfile()
    {
        $username = 'Pedro Francisco';
        $password = 'Admin*1234567';
        $morada = 'Leiria';
        $telefone = 912345678;

        $user = User::findOne(['username' => $username]);

        if (!$user) {
            $this->fail('Utilizador ' . $username . ' não encontrado');
        }

        if (Profile::find()->where(['telefone' => $telefone])->exists()) {
            $this->fail('O telefone inserido já está associado a outro Utilizador ' . $telefone);
        } else {
            $user->setPassword($password);
            $user->generateAuthKey();
            $this->assertTrue($user->save());

            $profile = Profile::findOne(['user_id' => $user->id]);
            $profile->morada = $morada;
            $profile->telefone = $telefone;
            $this->assertTrue($profile->save());
        }
    }

    public function testEditarUser()
    {
        $username = 'Pedro Francisco';

        $user = User::findOne(['username' => $username]);

        if (!$user) {
            $this->fail('Utilizador ' . $username . ' não encontrado');
        }

        $novoUsername = 'Tuga Francisco';
        $novoEmail = 'pedrofrancisco2@gmail.com';
        $password = 'Admin*1234567';

        if (User::find()->where(['username' => $novoUsername])->exists()) {
            $this->fail('O username inserido já está associado a outro Utilizador.');
        } else if (User::find()->where(['email' => $novoEmail])->exists()) {
            $this->fail('O email inserido já está associado a outro Utilizador.');
        } else {
            $user->username = $novoUsername;
            $user->email = $novoEmail;
            $user->setPassword($password);
            $user->generateAuthKey();
            $this->assertTrue($user->save());
        }
    }

    public function testEditarProfile()
    {
        $username = 'Pedro Francisco';

        $user = User::findOne(['username' => $username]);

        if (!$user) {
            $this->fail('Utilizador ' . $username . ' não encontrado');
        }

        $novoNif = '123456789';
        $novaMorada = 'Leiria';
        $novoTelefone = '912345678';

        if (Profile::find()->where(['nif' => $novoNif, 'user_id' => $user->id])->exists()) {
            $this->fail('O nif inserido já está associado a outro Utilizador.');
        } else if (Profile::find()->where(['telefone' => $novoTelefone, 'user_id' => $user->id])->exists()) {
            $this->fail('O telefone inserido já está associado a outro Utilizador.');
        } else {
            $profile = Profile::findOne(['user_id' => $user->id]);
            $profile->nif = $novoNif;
            $profile->morada = $novaMorada;
            $profile->telefone = $novoTelefone;
            $this->assertTrue($user->save());
        }
    }

    public function testApagarUtilizador()
    {
        $username = 'Pedro Francisco';

        $user = User::findOne(['username' => $username]);

        //verificar se o utilizador foi encontrado
        if (!$user) {
            $this->fail('User não encontrado');
        }

        $profile = Profile::findOne(['user_id' => $user->id]);

        //carrinho de compras
        $carrinho = Carrinhocompra::find()->where(['profile_id' => $profile->id])->one();

        if ($carrinho != null) {
            //linhas carrinho de compras
            $linhasCarrinho = Linhacarrinho::find()->where(['carrinhocompras_id' => $carrinho->id])->all();
            if ($linhasCarrinho != null) {
                foreach ($linhasCarrinho as $linha) {
                    $this->assertGreaterThan(0, $linha->delete());
                }
            }

            $this->assertGreaterThan(0, $carrinho->delete());
        }

        //avaliações produtos criados pelo Utilizador
        $avaliacoes = Avaliacao::find()->where(['profile_id' => $profile->id])->all();

        if ($avaliacoes != null) {

            foreach ($avaliacoes as $avaliacao) {
                $this->assertGreaterThan(0, $avaliacao->delete());
            }
        }

        //faturas
        $faturas = Fatura::find()->where(['profile_id' => $profile->id])->all();

        if ($faturas != null) {
            foreach ($faturas as $fatura) {
                $linhasFatura = Linhafatura::find()->where(['fatura_id' => $fatura->id])->all();

                //verifica se há linhas associadas à fatura
                if ($linhasFatura != null) {
                    foreach ($linhasFatura as $linhaFatura) {
                        $this->assertGreaterThan(0, $linhaFatura->delete());
                    }
                }

                $this->assertGreaterThan(0, $fatura->delete());
            }
        }

        //encomendas do Utilizador
        $encomendas = Encomenda::find()->where(['profile_id' => $profile->id])->all();

        if ($encomendas != null) {

            foreach ($encomendas as $encomenda) {
                $this->assertGreaterThan(0, $encomenda->delete());
            }
        }

        //favoritos do Utilizador
        $favoritos = Favorito::find()->where(['profile_id' => $profile->id])->all();

        if ($favoritos != null) {

            foreach ($favoritos as $favorito) {
                $this->assertGreaterThan(0, $favorito->delete());
            }
        }

        //cupões utilizados pelo Utilizador - (Tabela Uso Cupoes)
        $cupoes = Usocupao::find()->where(['profile_id' => $profile->id])->all();

        if ($cupoes != null) {

            foreach ($cupoes as $cupao) {
                $this->assertGreaterThan(0, $cupao->delete());
            }
        }

        $this->assertGreaterThan(0, $profile->delete());

        $this->assertGreaterThan(0, $user->delete());
    }
}