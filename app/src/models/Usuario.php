<?php
namespace App\Models;
use App\Config\Conexao, PDO;

class Usuario {
    public PDO $conn;
    public int $id;
    public string $nome;
    public string $email;
    public $senha;
    public string $data_criacao;

    public function __construct() {
        $db = new Conexao();
        $this->conn = $db->getConnection();
    }

    public function criar_usuario() {
        $query = "INSERT INTO usuarios (nome, email, senha, data_criacao) VALUES (:nome, :email, :senha, :data_criacao)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":nome", $this->nome);
        $stmt->bindValue(":email", $this->email);
        $stmt->bindValue(':data_criacao', $this->data_criacao);
        $senha_harsh = password_hash($this->senha, PASSWORD_BCRYPT);
        $stmt->bindValue(":senha", $senha_harsh);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function autenticar_usuario() {
        $query = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":email", $this->email);
        if (!$stmt->execute()) {
            return false;
        }
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (empty($usuario)) {
            return 'email_nao_encontrado';
        }

        if (password_verify($this->senha, $usuario['senha'])) {
            return $usuario;
        }
        return false;
    }

    public function atualizar_senha($senha_antiga) {
        $senha_verdadeira = $this->consultar_senha()['senha'];
        if (!password_verify($senha_antiga, $senha_verdadeira)) {
            return 'senha_errada';
        }
        $query = "UPDATE usuarios SET senha = :senha WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":id", $this->id);
        $senha_harsh = password_hash($this->senha, PASSWORD_BCRYPT);
        $stmt->bindValue(":senha", $senha_harsh);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function consultar_email() {
        $query = "SELECT id FROM usuarios WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":email", $this->email);

        if ($stmt->execute()) {
            if ($stmt->rowCount() == 1) {
                $id = $stmt->fetch(PDO::FETCH_ASSOC);
                return $id;
            } else {
                return 'nao_encontrado';
            } 
        }
        
        return false;
    }

    public function atualizar_usuario($email_antigo) {
        $query = "UPDATE usuarios SET nome = :nome, email = :email WHERE email = :email_antigo";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":nome", $this->nome);
        $stmt->bindValue(":email", $this->email);
        $stmt->bindValue(":email_antigo", $email_antigo);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    private function consultar_senha() {
        $query = "SELECT senha from usuarios WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":id", $this->id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}


