<?php
namespace App\Controllers;
use App\Models\Pagina, PDOException;

class PaginaController {
    private Pagina $pagina;

    public function __construct() {
        $this->pagina = new Pagina();
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

        $this->pagina->conteudo = $data['conteudo'];
        $this->pagina->titulo = $data['titulo'];

        date_default_timezone_set('America/Sao_Paulo');
        $dataAtual = date("Y-m-d H:i:s");
        $this->pagina->data_criacao = $dataAtual;
        $this->pagina->data_atualizacao = $dataAtual;
        try {
            $retorno = $this->pagina->criar_tabela();
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
            $data =  $this->pagina->ler_registros();
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
        $this->pagina->id = $id;
        try {
            $data =  $this->pagina->ler_registro();
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
        $this->pagina->id = $id;
        try {
            $data =  $this->pagina->deletar_registro();
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
        
        $this->pagina->id = $data['id'];
        $this->pagina->titulo = $data['titulo'];
        $this->pagina->conteudo = $data['conteudo'];
        date_default_timezone_set('America/Sao_Paulo');
        $dataAtual = date("Y-m-d H:i:s");
        $this->pagina->data_atualizacao = $dataAtual;
        try {
            $data =  $this->pagina->atualizar_registro();
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
