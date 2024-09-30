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
        $senhaHarsh = password_hash($this->senha, PASSWORD_BCRYPT);
        $stmt->bindValue(":senha", $senhaHarsh);

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
        $usuario = $stmt->fetch();

        if (empty($usuario)) {
            return 'email_nao_encontrado';
        }

        if (password_verify($this->senha, $usuario['senha'])) {
            return $usuario;
        }
        return false;
    }
}


