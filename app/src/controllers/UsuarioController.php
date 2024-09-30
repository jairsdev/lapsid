<?php
namespace App\Controllers;
use App\Models\Usuario, PDOException;

class UsuarioController {
    private Usuario $usuario;

    public function __construct() {
        header('Content-Type: application/json; charset=UTF-8');
        $this->usuario = new Usuario();
    }

    public function login($data) {
        if (empty($data['email'])) {
            echo json_encode(['sucess' => false, 'message' => 'O campo email está vazio']);
            return;
        }

        if (empty($data['senha'])) {
            echo json_encode(['sucess' => false, 'message' => 'O campo senha está vazio']);
            return;
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['sucess' => false, 'message' => 'O email é inválido']);
            return;
        }

        $this->usuario->email = $data['email'];
        $this->usuario->senha = $data['senha'];

        try {
            $retorno = $this->usuario->autenticar_usuario();
        } catch (PDOException $err) {
            echo json_encode(['sucess' => false,'message' => 'Houve um problema interno, tente novamente mais tarde']);
            return;
        }

        if ($retorno == 'email_nao_encontrado') {
            echo json_encode(['sucess' => false, 'message' => 'Email inválido']);
            return;
        } elseif ($retorno == false) {
            echo json_encode(['success' => false, 'message' => 'Usuário ou senha inválidos.']);
            return;
        } else {
            echo json_encode(['success' => true, 'message' => 'Login bem-sucedido']);
            session_start();
            $_SESSION['usuario_id'] = $retorno['id'];
            return;
        }
    }

    public function create_login($data) {
        if (empty($data['email'])) {
            echo json_encode(['sucess' => false, 'message' => 'O campo email está vazio']);
            return;
        }

        if (empty($data['senha'])) {
            echo json_encode(['sucess' => false, 'message' => 'O campo senha está vazio']);
            return;
        }

        if (strlen($data['senha']) < 8) {
            echo json_encode(['sucess' => false, 'message' => 'A senha precisa ter 8 digitos']);
            return;
        }

        if (strlen($data['senha']) > 255) {
            echo json_encode(['sucess' => false, 'message' => 'A senha não pode passar de 255 digitos']);
            return;
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['sucess' => false, 'message' => 'O email é inválido']);
            return;
        }

        $this->usuario->nome = $data['nome'];
        $this->usuario->email = $data['email'];
        $this->usuario->senha = $data['senha'];
        date_default_timezone_set('America/Sao_Paulo');
        $dataAtual = date("Y-m-d H:i:s");
        $this->usuario->data_criacao = $dataAtual;
        try {
            $retorno = $this->usuario->criar_usuario();
        } catch (PDOException $err) {
            if ($err->getCode() == 23000) {
                echo json_encode(['sucess' => false, 'message' => 'O email já está cadastrado']);
                return;
            }
            echo json_encode(['sucess' => false, 'message' => 'Houve um problema interno, tente novamente mais tarde ' . $err->getCode()]);
            return;
        }

        if ($retorno) {
            echo json_encode(['sucess' => true, 'message' => 'Usuário criado com sucesso']);
            return;
        } else {
            echo json_encode(['message' => 'Erro ao criar o usuário']);
            return;
        }
    }
}