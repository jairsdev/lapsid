<?php
namespace App\Controllers;
use App\Models\Parceria, PDOException;

class ParceriaController {
    private Parceria $parceria;

    public function __construct() {
        $this->parceria = new Parceria();
        header('Content-Type: application/json; charset=UTF-8');
    }

    public function insert($data) {
        if (empty($data['nome'])) {
            echo json_encode(['sucess' => false, 'message' => 'O campo nome está vazio']);
            return;
        }

        if (empty($data['descricao'])) {
            echo json_encode(['sucess' => false, 'message' => 'O campo parceria está vazio']);
            return;
        }

        $this->parceria->descricao = $data['descricao'];
        $this->parceria->nome = $data['nome'];

        date_default_timezone_set('America/Sao_Paulo');
        $dataAtual = date("Y-m-d H:i:s");
        $this->parceria->data_criacao = $dataAtual;
        $this->parceria->data_atualizacao = $dataAtual;
        try {
            $retorno = $this->parceria->criar_tabela();
        } catch (PDOException $err) {
            echo json_encode(['sucess' => false, 'message' => 'Houve um problema interno, tente novamente mais tarde']);
            return;
        }

        if ($retorno) {
            echo json_encode(['sucess' => true, 'message' => 'Parceria da página criada com sucesso']);
            return;
        } else {
            echo json_encode(['sucess' => false, 'message' => 'Erro ao criar a parceria da página']);
            return;
        }
    }

    public function index_all() {
        try {
            $data =  $this->parceria->ler_registros();
        } catch (PDOException $err) {
            echo json_encode(['sucess' => false, 'message' => 'Houve um problema interno, tente novamente mais tarde']);
            return;
        }

        if (empty($data)) {
            echo json_encode(['sucess' => false, 'message' => 'Não existe nenhuma parceria nesse página']);
            return;
        }

        echo json_encode($data);
    }

    public function index($id) {
        $this->parceria->id = $id;
        try {
            $data =  $this->parceria->ler_registro();
        } catch (PDOException $err) {
            echo json_encode(['sucess' => false, 'message' => 'Houve um problema interno, tente novamente mais tarde']);
            return;
        }
        
        if (empty($data)) {
            echo json_encode(['sucess' => false, 'message' => 'Parceria não encontrada']);
            return;
        }

        echo json_encode($data);
        return;
    }

    public function delete($id) {
        $this->parceria->id = $id;
        try {
            $data =  $this->parceria->deletar_registro();
        } catch (PDOException $err) {
            echo json_encode(['sucess' => false, 'message' => 'Houve um problema interno, tente novamente mais tarde']);
            return;
        }
        
        if ($data === 0) {
            echo json_encode(['sucess' => false, 'message' => 'Esse parceria não existe']);
            return;
        }

        echo json_encode(['sucess' => true, 'message' => 'Parceria deletada com sucesso']);
    }

    public function update($data) {
        if (empty($data['nome'])) {
            echo json_encode(['sucess' => false, 'message' => 'O campo nome está vazio']);
            return;
        }

        if (empty($data['descricao'])) {
            echo json_encode(['sucess' => false, 'message' => 'O campo parceria está vazio']);
            return;
        }
        
        $this->parceria->id = $data['id'];
        $this->parceria->nome = $data['nome'];
        $this->parceria->descricao = $data['descricao'];
        date_default_timezone_set('America/Sao_Paulo');
        $dataAtual = date("Y-m-d H:i:s");
        $this->parceria->data_atualizacao = $dataAtual;
        try {
            $data =  $this->parceria->atualizar_registro();
        } catch (PDOException $err) {
            echo json_encode(['sucess' => false, 'message' => 'Houve um problema interno, tente novamente mais tarde']);
            return;
        }
        
        if ($data === 0) {
            echo json_encode(['sucess' => false, 'message' => 'Esse parceria não existe']);
            return;
        }

        echo json_encode(['sucess' => true, 'message' => 'Parceria atualizada com sucesso']);
    }
}