<?php 
namespace App\Controllers;
use App\Models\Noticia, PDOException;

class NoticiaController {
    private Noticia $noticia;

    public function __construct() {
        $this->noticia = new noticia();
        header('Content-Type: application/json; charset=UTF-8');
    }

    public function insert($data) {
        if (empty($data['titulo'])) {
            echo json_encode(['sucess' => false, 'message' => 'O campo título está vazio']);
            return;
        }

        if (empty($data['conteudo'])) {
            echo json_encode(['sucess' => false, 'message' => 'O campo conteúdo está vazio']);
            return;
        }

        $this->noticia->conteudo = $data['conteudo'];
        $this->noticia->titulo = $data['titulo'];
        $this->noticia->autor_id = $data['autor_id'];
        date_default_timezone_set('America/Sao_Paulo');
        $dataAtual = date("Y-m-d H:i:s");
        $this->noticia->data_criacao = $dataAtual;
        $this->noticia->data_atualizacao = $dataAtual;
        try {
            $retorno = $this->noticia->criar_tabela();
        } catch (PDOException $err) {
            echo json_encode(['sucess' => false, 'message' => 'Houve um problema interno, tente novamente mais tarde']);
            return;
        }

        if ($retorno) {
            echo json_encode(['sucess' => true, 'message' => 'Conteúdo da página criada com sucesso']);
            return;
        } else {
            echo json_encode(['sucess' => false, 'message' => 'Erro ao criar o conteúdo da página']);
            return;
        }
    }

    public function index_all() {
        try {
            $data =  $this->noticia->ler_registros();
        } catch (PDOException $err) {
            echo json_encode(['sucess' => false, 'message' => 'Houve um problema interno, tente novamente mais tarde']);
            return;
        }

        if (empty($data)) {
            echo json_encode(['sucess' => false, 'message' => 'Não existe nenhum conteúdo nesse página']);
            return;
        }

        echo json_encode($data);
    }

    public function index($id) {
        $this->noticia->id = $id;
        try {
            $data =  $this->noticia->ler_registro();
        } catch (PDOException $err) {
            echo json_encode(['sucess' => false, 'message' => 'Houve um problema interno, tente novamente mais tarde']);
            return;
        }
        
        if (empty($data)) {
            echo json_encode(['sucess' => false, 'message' => 'Conteúdo não encontrado']);
            return;
        }

        echo json_encode($data);
        return;
    }

    public function delete($id) {
        $this->noticia->id = $id;
        try {
            $data =  $this->noticia->deletar_registro();
        } catch (PDOException $err) {
            echo json_encode(['sucess' => false, 'message' => 'Houve um problema interno, tente novamente mais tarde']);
            return;
        }
        
        if ($data === 0) {
            echo json_encode(['sucess' => false, 'message' => 'Esse conteúdo não existe']);
            return;
        }

        echo json_encode(['sucess' => true, 'message' => 'Conteúdo deletado com sucesso']);
    }

    public function update($data) {
        if (empty($data['titulo'])) {
            echo json_encode(['sucess' => false, 'message' => 'O campo título está vazio']);
            return;
        }

        if (empty($data['conteudo'])) {
            echo json_encode(['sucess' => false, 'message' => 'O campo conteúdo está vazio']);
            return;
        }
        
        $this->noticia->id = $data['id'];
        $this->noticia->titulo = $data['titulo'];
        $this->noticia->conteudo = $data['conteudo'];
        $this->noticia->autor_id = $data['autor_id'];
        date_default_timezone_set('America/Sao_Paulo');
        $dataAtual = date("Y-m-d H:i:s");
        $this->noticia->data_atualizacao = $dataAtual;
        try {
            $data =  $this->noticia->atualizar_registro();
        } catch (PDOException $err) {
            echo json_encode(['sucess' => false, 'message' => 'Houve um problema interno, tente novamente mais tarde']);
            return;
        }
        
        if ($data === 0) {
            echo json_encode(['sucess' => false, 'message' => 'Esse conteúdo não existe']);
            return;
        }

        echo json_encode(['sucess' => true, 'message' => 'Conteúdo atualizado com sucesso']);
    }
}