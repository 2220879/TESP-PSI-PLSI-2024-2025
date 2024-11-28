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
        //se existirem imagens para upload
        if ($this->validate()) {
            foreach ($this->imagens as $file) {
                //gerar uma random string para o nome do ficheiro
                $key = Yii::$app->getSecurity()->generateRandomString();

                //caminho completo para salvar a imagem
                $backendPath = Yii::getAlias('@backend/web/uploads/') . $key . '.' . $file->extension;

                //local da pasta de uploads
                $backendDir = dirname($backendPath);

                //verificar se a pasta existe, caso contrário criar a pasta
                if (!is_dir($backendDir)) {
                    if (!mkdir($backendDir, 0777, true) && !is_dir($backendDir)) {
                        throw new \Exception('Falha ao criar a pasta de uploads: ' . $backendDir);
                    }
                }

                //verificar se a pasta permite gravar os ficheiros
                if (!is_writable($backendDir)) {
                    throw new \Exception('A pasta não permite gravar: ' . $backendDir);
                }

                //guardar o ficheiro na pasta especificada
                if (!$file->saveAs($backendPath)) {
                    throw new \Exception('Erro ao guardar o ficheiro em: ' . $backendPath);
                }

                //criar o registo na base de dados
                $imagem = new Imagem();
                $imagem->filename = $key . '.' . $file->extension;
                $imagem->produto_id = $id;

                //guardar o registo na base de dados
                if (!$imagem->save()) {
                    throw new \Exception('Erro ao salvar os dados da imagem na base de dados.');
                }
            }
        }
    }

    public function update($id)
    {
        //se existirem imagens para upload
        if ($this->validate()) {

            //selecionar na base de dados, a imagem pretendida para editar
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