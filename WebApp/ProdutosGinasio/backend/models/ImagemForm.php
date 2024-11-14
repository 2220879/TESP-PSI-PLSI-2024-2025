<?php

namespace backend\models;

use common\models\Imagem;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class ImagemForm extends Model
{
    /**
     * @var UploadedFile[]
     */
    public $imagens;

    public function rules()
    {
        return [
            [['imagens'], 'file', 'maxFiles' => 0, 'extensions' => ['png', 'jpg', 'jpeg']],
        ];
    }

    public function upload($id)
    {
        //se existir imagens para upload
        if ($this->validate()) {
            //iterar as imagens a inserir
            foreach ($this->imagens as $file) {
                //gerar uma random string
                $key = Yii::$app->getSecurity()->generateRandomString();

                //atribuir as imagens em duas pastas
                $backendPath = Yii::getAlias('@backend/web/uploads/') . $key . '.' . $file->extension;
                $frontendPath = Yii::getAlias('@frontend/web/uploads/') . $key . '.' . $file->extension;

                //guardar as imagens
                $file->saveAs($backendPath);
                $file->saveAs($frontendPath);

                //criar o registo na base dados
                $imagem = new Imagem();
                $imagem->filename = $key . '.' . $file->extension;
                $imagem->produto_id = $id;

                //guardar o registo na base dados
                $imagem->save();
            }
        }
    }

    public function update($id)
    {
        //se existir imagens para upload
        if ($this->validate()) {

            $imagem = Imagem::findOne($id);
            $file = $this->imagens[0];
            $key = Yii::$app->getSecurity()->generateRandomString();

            //atribuir as imagens em duas pastas
            $backendPath = Yii::getAlias('@backend/web/uploads/') . $key . '.' . $file->extension;
            $frontendPath = Yii::getAlias('@frontend/web/uploads/') . $key . '.' . $file->extension;

            // Se necessário, excluir a imagem antiga das pastas (caso você queira limpar)
            $oldBackendPath = Yii::getAlias('@backend/web/uploads/') . $imagem->filename;
            $oldFrontendPath = Yii::getAlias('@frontend/web/uploads/') . $imagem->filename;

            if (file_exists($oldBackendPath)) {
                unlink($oldBackendPath);  // Deletar a imagem antiga do backend
            }
            if (file_exists($oldFrontendPath)) {
                unlink($oldFrontendPath);  // Deletar a imagem antiga do frontend
            }

            //guardar as imagens
            $file->saveAs($backendPath);
            $file->saveAs($frontendPath);

            //alterar o registo na base dados
            $imagem->filename = $key . '.' . $file->extension;

            //guardar o registo na base dados
            $imagem->save();
        }
    }

    public function deleteAll($id)
    {
        //pesquisa na base dados as imagens referentes a um produto
        if ($imagens = Imagem::find()->where(['produto_id' => $id])->all()) {
            //iterar as imagens encontradas
            foreach ($imagens as $imagem) {
                //selecionar a imagem da pasta de imagens
                $backendPath = Yii::getAlias('@backend/web/uploads/') . $imagem->filename;
                $frontendPath = Yii::getAlias('@frontend/web/uploads/') . $imagem->filename;

                //se encontrar a imagem
                if (file_exists($backendPath)) {
                    //apagar a imagem no backend
                    unlink($backendPath);
                }
                //se encontrar a imagem
                if (file_exists($frontendPath)) {
                    //apagar a imagem no frontend
                    unlink($frontendPath);
                }

                //apagar na base dados
                $imagem->delete();
            }
        }
    }

    public function delete($id)
    {
        //pesquisa na base dados as imagens referentes a um produto
        if ($imagem = Imagem::find()->where(['id' => $id])->one()) {
            //selecionar a imagem da pasta de imagens
            $backendPath = Yii::getAlias('@backend/web/uploads/') . $imagem->filename;
            $frontendPath = Yii::getAlias('@frontend/web/uploads/') . $imagem->filename;

            //se encontrar a imagem
            if (file_exists($backendPath)) {
                //apagar a imagem no backend
                unlink($backendPath);
            }
            //se encontrar a imagem
            if (file_exists($frontendPath)) {
                //apagar a imagem no frontend
                unlink($frontendPath);
            }

            //apagar na base dados
            $imagem->delete();
        }
    }
}