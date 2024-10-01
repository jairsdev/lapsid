<?php
namespace App\Models;
use App\Config\Conexao, PDO;

class Noticia {
    public PDO $conn;
    public int $id;
    public string $titulo;
    public int $autor_id;
    public string $conteudo;
    public string $data_criacao;
    public string $data_atualizacao;


    public function __construct() {
        $db = new Conexao();
        $this->conn = $db->getConnection();
    }

    public function criar_tabela() {
        $query = "INSERT INTO noticias (titulo, conteudo, autor_id, data_criacao, data_atualizacao) VALUES (:titulo, :conteudo, :autor_id, :data_criacao, :data_atualizacao)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":titulo", $this->titulo, PDO::PARAM_STR);
        $stmt->bindValue(":conteudo", $this->conteudo, PDO::PARAM_STR);
        $stmt->bindValue(":autor_id", $this->autor_id, PDO::PARAM_INT);
        $stmt->bindValue(":data_criacao", $this->data_criacao, PDO::PARAM_STR);
        $stmt->bindValue(":data_atualizacao", $this->data_atualizacao, PDO::PARAM_STR);
           
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function ler_registros() {
        $query = "SELECT * FROM noticias ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function ler_registro() {
        $query = "SELECT * FROM noticias WHERE id = :id ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizar_registro() {
        $query = "UPDATE noticias SET titulo = :titulo, conteudo = :conteudo, autor_id = :autor_id, data_atualizacao = :data_atualizacao WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);
        $stmt->bindValue(":titulo", $this->titulo, PDO::PARAM_STR);
        $stmt->bindValue(":conteudo", $this->conteudo, PDO::PARAM_STR);
        $stmt->bindValue(":autor_id",$this->autor_id, PDO::PARAM_INT);
        $stmt->bindValue(":data_atualizacao", $this->data_atualizacao, PDO::PARAM_STR);

        if ($stmt->execute()) {
            return $stmt->rowCount();
        }
        return false;
    }

    public function deletar_registro() {
        $query = "DELETE FROM noticias WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return $stmt->rowCount();
        }
        return false;
    }
}