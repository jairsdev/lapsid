<?php 
namespace App\Controllers;
use App\Models\Equipe, PDOException;

class EquipeController {
    private Equipe $equipe;

    public function __construct() {
        $this->equipe = new Equipe();
        header('Content-Type: application/json; charset=UTF-8');
    }

    public function insert($data) {
        if (empty($data['nome'])) {
            echo json_encode(['sucess' => false, 'message' => 'O campo nome está vazio']);
            return;
        }

        if (empty($data['email'])) {
            echo json_encode(['sucess' => false, 'message' => 'O campo email está vazio']);
            return;
        }

        if (empty($data['titulo'])) {
            echo json_encode(['sucess' => false, 'message' => 'O campo titulo está vazio']);
            return;
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['sucess' => false, 'message' => 'O email é inválido']);
            return;
        }

        $this->equipe->nome = $data['nome'];
        $this->equipe->email = $data['email'];
        $this->equipe->titulo = $data['titulo'];
        if (empty($data['lattes'])) {
            $this->equipe->lattes = "null";
        } else {
            $this->equipe->lattes = $data['lattes'];
        }

        try {
            $retorno = $this->equipe->criar_tabela();
        } catch (PDOException $err) {
            echo json_encode(['sucess' => false, 'message' => 'Houve um problema interno, tente novamente mais tarde']);
            return;
        }

        if ($retorno) {
            echo json_encode(['sucess' => true, 'message' => 'Integrante adicionado com sucesso']);
            return;
        } else {
            echo json_encode(['sucess' => false, 'message' => 'Erro ao criar o integrante']);
            return;
        }
    }

    public function index_all() {
        try {
            $data =  $this->equipe->ler_registros();
        } catch (PDOException $err) {
            echo json_encode(['sucess' => false, 'message' => 'Houve um problema interno, tente novamente mais tarde']);
            return;
        }

        if (empty($data)) {
            echo json_encode(['sucess' => false, 'message' => 'Não existe nenhum integrante ainda']);
            return;
        }

        echo json_encode($data);
    }

    public function index($id) {
        $this->equipe->id = $id;
        try {
            $data =  $this->equipe->ler_registro();
        } catch (PDOException $err) {
            echo json_encode(['sucess' => false, 'message' => 'Houve um problema interno, tente novamente mais tarde']);
            return;
        }
        
        if (empty($data)) {
            echo json_encode(['sucess' => false, 'message' => 'Integrante não encontrado']);
            return;
        }

        echo json_encode($data);
        return;
    }

    public function delete($id) {
        $this->equipe->id = $id;
        try {
            $data =  $this->equipe->deletar_registro();
        } catch (PDOException $err) {
            echo json_encode(['sucess' => false, 'message' => 'Houve um problema interno, tente novamente mais tarde']);
            return;
        }
        
        if ($data === 0) {
            echo json_encode(['sucess' => false, 'message' => 'Integrante não encontrado']);
            return;
        }

        echo json_encode(['sucess' => true, 'message' => 'Integrante deletado com sucesso']);
    }

    public function update($data) {
        if (empty($data['nome'])) {
            echo json_encode(['sucess' => false, 'message' => 'O campo nome está vazio']);
            return;
        }

        if (empty($data['email'])) {
            echo json_encode(['sucess' => false, 'message' => 'O campo email está vazio']);
            return;
        }

        if (empty($data['titulo'])) {
            echo json_encode(['sucess' => false, 'message' => 'O campo titulo está vazio']);
            return;
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['sucess' => false, 'message' => 'O email é inválido']);
            return;
        }
        
        $this->equipe->nome = $data['nome'];
        $this->equipe->email = $data['email'];
        $this->equipe->id = $data['id'];
        $this->equipe->titulo = $data['titulo'];
        if (empty($data['lattes'])) {
            $this->equipe->lattes = "null";
        } else {
            $this->equipe->lattes = $data['lattes'];
        }

        try {
            $data =  $this->equipe->atualizar_registro();
        } catch (PDOException $err) {
            echo json_encode(['sucess' => false, 'message' => 'Houve um problema interno, tente novamente mais tarde']);
            return;
        }
        
        if ($data === 0) {
            echo json_encode(['sucess' => false, 'message' => 'Integrante não encontrado']);
            return;
        }

        echo json_encode(['sucess' => true, 'message' => 'Integrante atualizado com sucesso']);
    }
}