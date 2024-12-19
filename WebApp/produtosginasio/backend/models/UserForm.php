<?php

namespace backend\models;

use frontend\models\Carrinhocompra;
use Yii;
use yii\base\Model;
use common\models\User;
use common\models\Profile;

class UserForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $role;
    public $status;
    public $nif;
    public $morada;
    public $telefone;

    const SCENARIO_CREATE = 'create';

    public function rules()
    {


        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'O nome de utilizador já existe.', 'on' => self::SCENARIO_CREATE],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'O email já existe.', 'on' => self::SCENARIO_CREATE],

            ['password', 'required', 'on' => self::SCENARIO_CREATE],
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength'], 'message' => 'A senha deve ter no mínimo 12 caracteres.'],
            ['password', 'string', 'max' => Yii::$app->params['user.passwordMaxLength'], 'message' => 'A senha deve ter no máximo 16 caracteres.'],
            [['password'], 'match', 'pattern' => Yii::$app->params['user.passwordPattern'], 'message' => 'A senha deve conter pelo menos uma letra maiúscula, números e símbolos especiais.'],

            ['role', 'required'],

            ['status', 'required'],

            ['nif', 'required'],
            ['nif', 'unique', 'targetClass' => '\common\models\Profile', 'message' => 'O nif inserido já existe.', 'on' => self::SCENARIO_CREATE],

            ['morada', 'required'],

            ['telefone', 'required'],
            ['telefone', 'unique', 'targetClass' => '\common\models\Profile', 'message' => 'O telefone inserido já existe.', 'on' => self::SCENARIO_CREATE],
        ];
    }

    public function create()
    {
        //valida os dados do formulário - vista
        if ($this->validate()) {
            //cria um novo Utilizador
            $user = new User();

            //atribui os respetivos os dados ao novo user
            $user->username = $this->username;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->status = $this->status;

            //se correu tudo bem ao gravar os dados do user
            if ($user->save(false)) {

                //atribui a role
                $auth = Yii::$app->authManager;
                $role = $auth->getRole($this->role);
                $auth->assign($role, $user->id);

                //cria um novo perfil ao utilizador criado
                $profile = new Profile();

                //atribui o respetivo valor para cada campo do perfil
                $profile->nif = $this->nif;
                $profile->morada = $this->morada;
                $profile->telefone = $this->telefone;
                $profile->user_id = $user->id;

                //se o registo do perfil foi concluído
                if ($profile->save()) {

                    if ($this->role == 'cliente') {
                        $carrinhoCompras = new Carrinhocompra();
                        $carrinhoCompras->quantidade = 0;
                        $carrinhoCompras->valorTotal = 0.00;
                        $carrinhoCompras->profile_id = $profile->id;
                        $carrinhoCompras->save();
                    }
                    return $user->id;
                }
            }
        }
        return null;
    }

    public function update($id)
    {
        //valida os dados do formulário - vista
        if ($this->validate()) {

            //seleciona o user a editar
            $user = User::findOne(['id' => $id]);

            //altera os respetivos os dados do user a editar
            $user->username = $this->username;
            $user->email = $this->email;

            //se o campo da password não estiver vazia, edita a password
            if ($this->password != null) {
                $user->setPassword($this->password);
            }

            $user->generateAuthKey();
            $user->status = $this->status;

            //se a alteração dos dados do user foram gravados com sucesso
            if ($user->save(false)) {

                //vai buscar a role do user a editar
                $roleUser = key(Yii::$app->authManager->getRolesByUser($user->id)); // Obtém a role atual associada ao usuário

                //verifica se o campo da role foi alterada
                if ($roleUser != $this->role) {
                    // Se a role foi alterada, atribui a nova role

                    //remove a role antiga
                    $auth = Yii::$app->authManager;
                    $auth->revoke($auth->getRole($roleUser), $user->id);

                    //atribui a nova role
                    $role = $auth->getRole($this->role);
                    $auth->assign($role, $user->id);
                }

                //seleciona o perfil do user a editar
                $profile = Profile::findOne(['user_id' => $user->id]);

                //altera os respetivos os dados do perfil do user a editar
                $profile->nif = $this->nif;
                $profile->morada = $this->morada;
                $profile->telefone = $this->telefone;

                //se o registo do perfil foi concluído
                if ($profile->save()) {
                    return true;
                }
            }
        }
        return null;
    }
}