<?php

use yii\db\Migration;

/**
 * Class m241021_154947_init_rbac
 */
class m241021_154947_init_rbac extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m241021_154947_init_rbac cannot be reverted.\n";

        return false;
    }

    public function up()
    {
        $auth = Yii::$app->authManager;

        // add "createPost" permission
        $createPost = $auth->createPermission('createPost');
        $createPost->description = 'Create a post';
        $auth->add($createPost);

        // add "updatePost" permission
        $updatePost = $auth->createPermission('updatePost');
        $updatePost->description = 'Update post';
        $auth->add($updatePost);

        // add "author" role and give this role the "createPost" permission
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $createPost);

        // add "admin" role and give this role the "updatePost" permission
        // as well as the permissions of the "author" role
        $funcionario = $auth->createRole('funcionario');
        $auth->add($funcionario);
        $auth->addChild($admin, $updatePost);
        $auth->addChild($admin, $funcionario);

        //adicionar a role cliente
        $cliente = $auth->createRole('cliente');
        $auth->add($cliente);

        //adicionar a role visitante
        $visitante = $auth->createRole('visitante');
        $auth->add($visitante);

        // Assign roles to users. 1 and 2 are IDs returned by IdentityInterface::getId()
        // usually implemented in your User model.
        $auth->assign($visitante, 4);
        $auth->assign($cliente, 3);
        $auth->assign($funcionario, 2);
        $auth->assign($admin, 1);
    }

    public function down()
    {
        $auth = Yii::$app->authManager;

        $auth->removeAll();
    }
}
