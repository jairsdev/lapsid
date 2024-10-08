<?php
namespace App\Models;
use App\Config\Conexao, PDO;

class Equipe {
    public PDO $conn;
    public int $id;
    public string $nome;
    public string $email;
    public string $titulo;
    public string $lattes;

    public function __construct() {
        $db = new Conexao();
        $this->conn = $db->getConnection();
    }

    public function criar_tabela() {
        $query = "INSERT INTO equipe (nome, email, titulo, lattes) VALUES (:nome, :email, :titulo, :lattes)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":nome", $this->nome, PDO::PARAM_STR);
        $stmt->bindValue(":email", $this->email);
        $stmt->bindValue(":titulo", $this->titulo);
        $stmt->bindValue(":lattes", $this->lattes);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function ler_registros() {
        $query = "SELECT * FROM equipe ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function ler_registro() {
        $query = "SELECT * FROM equipe WHERE id = :id ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizar_registro() {
        $query = "UPDATE equipe SET nome = :nome, email = :email, titulo = :titulo, lattes = :lattes WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);
        $stmt->bindValue(":nome", $this->nome);
        $stmt->bindValue(":email", $this->email);
        $stmt->bindValue(":titulo", $this->titulo);
        $stmt->bindValue(":lattes", $this->lattes);
        if ($stmt->execute()) {
            return $stmt->rowCount();
        }
        return false;
    }

    public function deletar_registro() {
        $query = "DELETE FROM equipe WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return $stmt->rowCount();
        }
        return false;
    }
}
