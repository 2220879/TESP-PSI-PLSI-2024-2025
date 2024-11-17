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

                //atribuir as imagens na pasta
                $backendPath = Yii::getAlias('@backend/web/uploads/') . $key . '.' . $file->extension;

                // Verificar e criar a pasta de backend se não existir
                $backendDir = dirname($backendPath);
                if (!is_dir($backendDir)) {
                    mkdir($backendDir, 777, true); // Cria o diretório com permissões e recursivamente
                }

                //guardar as imagens
                $file->saveAs($backendPath);

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

            //atribuir a imagem na pasta
            $backendPath = Yii::getAlias('@backend/web/uploads/') . $key . '.' . $file->extension;

            //verificar se a imagem antiga a apagar existe na pasta
            $oldBackendPath = Yii::getAlias('@backend/web/uploads/') . $imagem->filename;

            if (file_exists($oldBackendPath)) {
                unlink($oldBackendPath);
            }

            //guardar a imagem
            $file->saveAs($backendPath);

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

                //se encontrar a imagem
                if (file_exists($backendPath)) {
                    //apagar a imagem na pasta
                    unlink($backendPath);
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
            //selecionar a imagem na pasta de imagens
            $backendPath = Yii::getAlias('@backend/web/uploads/') . $imagem->filename;

            //se encontrar a imagem
            if (file_exists($backendPath)) {
                //apagar a imagem na pasta
                unlink($backendPath);
            }

            //apagar na base dados
            $imagem->delete();
        }
    }
}