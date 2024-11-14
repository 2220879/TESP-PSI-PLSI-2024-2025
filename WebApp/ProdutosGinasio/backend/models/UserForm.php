<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use common\models\User;
use common\models\Profile;

class UserForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $nif;
    public $morada;
    public $telefone;

    public function rules()
    {
        return [
            [['username', 'email', 'password'], 'required'],
            ['email', 'email'],
            ['password', 'validatePassword'],
        ];
    }

    public function create()
    {
        if (!$this->hasErrors()) {
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->save(false);

            if ($user->validate()) {

                $auth = Yii::$app->authManager;
                $funcionario = $auth->getRole('funcionario');
                $auth->assign($funcionario, $user->getId());

                $profile = new Profile();
                $profile->nif = $this->nif;
                $profile->morada = $this->morada;
                $profile->telefone = $this->telefone;
                $profile->user = $user->id;
                $profile->save();
            }

            return true;
        }
    }
}