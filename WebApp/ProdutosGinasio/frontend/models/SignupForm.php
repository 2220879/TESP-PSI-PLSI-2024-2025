<?php

namespace frontend\models;

use common\models\Profile;
use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $nif;
    public $morada;
    public $telefone;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'O nome de utilizador já existe.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'O email já existe.'],

            ['password', 'required'],
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength'], 'message' => 'A senha deve ter no mínimo 12 caracteres.'],
            ['password', 'string', 'max' => Yii::$app->params['user.passwordMaxLength'], 'message' => 'A senha deve ter no máximo 16 caracteres.'],
            [['password'], 'match', 'pattern' => Yii::$app->params['user.passwordPattern'], 'message' => 'A senha deve conter pelo menos uma letra maiúscula, números e símbolos especiais.'],

            ['nif', 'required'],
            ['nif', 'unique', 'targetClass' => '\common\models\Profile', 'message' => 'O nif inserido já existe.'],

            ['morada', 'required'],

            ['telefone', 'required'],
            ['telefone', 'unique', 'targetClass' => '\common\models\Profile', 'message' => 'O telefone inserido já existe.'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function signup()
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
            $user->status = User::STATUS_ACTIVE;

            //se correu tudo bem ao gravar os dados do user
            if ($user->save(false)) {

                //atribui a role
                $auth = Yii::$app->authManager;
                $cliente = $auth->getRole('cliente');
                $auth->assign($cliente, $user->getId());

                //cria um novo perfil ao utilizador criado
                $profile = new Profile();
                
                //atribui o respetivo valor para cada campo do perfil
                $profile->nif = $this->nif;
                $profile->morada = $this->morada;
                $profile->telefone = $this->telefone;
                $profile->user_id = $user->id;

                //se o registo do perfil foi concluído
                if ($profile->save()) {
                    return true;
                }
            }
        }
        return null;
    }

    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail($user)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();
    }
}
