<?php
    class Publicacao{
        // Connection
        private $conn;
        // Table
        private $db_table = "publicacao";
        // Columns
        public $idpub;
        public $titulo;
        public $descricao;
        public $imagem;
        public $data;
        public $idperfil;

        // Db connection
        public function __construct($db){
            $this->conn = $db;
        }

        // GET ALL
        public function getPublicacao(){
            $sqlQuery = "SELECT IDPub, Titulo, Descricao, Imagem, Data, IDPerfil FROM " . $this->db_table . "";
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute();
            return $stmt;
        }
        // CREATE
        public function createPublicacao(){
            $sqlQuery = "INSERT INTO " . $this->db_table . " SET
                        Titulo = :titulo, 
                        Descricao = :descricao, 
                        Imagem = :imagem, 
                        Data = :data, 
                        IDPerfil = :idperfil";

            $stmt = $this->conn->prepare($sqlQuery);

            
            $this->titulo = htmlspecialchars(strip_tags($this->titulo));
            $this->descricao = htmlspecialchars(strip_tags($this->descricao));
            $this->imagem = htmlspecialchars(strip_tags($this->imagem));
            $this->data = htmlspecialchars(strip_tags($this->data));
            $this->idperfil = htmlspecialchars(strip_tags($this->idperfil));

            // bind data
            $stmt->bindParam(":titulo", $this->titulo);
            $stmt->bindParam(":descricao", $this->descricao);
            $stmt->bindParam(":imagem", $this->imagem);
            $stmt->bindParam(":data", $this->data);
            $stmt->bindParam(":idperfil", $this->idperfil);

            if($stmt->execute()){
               return true;
            }
            return false;
        }
        // READ single
        public function getPerfilPublicacao($idperfil)
        {
            $sqlQuery = "SELECT IDPub, Titulo, Descricao, Imagem, Data, IDPerfil FROM " . $this->db_table . " WHERE IDPerfil = ".$idperfil."";
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute();
            return $stmt;
        }
        // UPDATE
        public function updatePublicacao(){
            $sqlQuery = "UPDATE
                        ". $this->db_table ."
                    SET
                        Titulo = :titulo,
                        Descricao = :descricao,
                        Imagem = :imagem,
                        Data = :data,
                        IDPerfil = :idperfil
                    WHERE 
                        IDPub = :idpub";

            $stmt = $this->conn->prepare($sqlQuery);

            $this->titulo = htmlspecialchars(strip_tags($this->titulo));
            $this->descricao = htmlspecialchars(strip_tags($this->descricao));
            $this->imagem = htmlspecialchars(strip_tags($this->imagem));
            $this->data = htmlspecialchars(strip_tags($this->data));
            $this->idperfil = htmlspecialchars(strip_tags($this->idperfil));
            $this->idpub = htmlspecialchars(strip_tags($this->idpub));

            // bind data
            $stmt->bindParam(":titulo", $this->titulo);
            $stmt->bindParam(":descricao", $this->descricao);
            $stmt->bindParam(":imagem", $this->imagem);
            $stmt->bindParam(":data", $this->data);
            $stmt->bindParam(":idperfil", $this->idperfil);
            $stmt->bindParam(":idpub", $this->idpub);

            if($stmt->execute()){
               return true;
            }
            return false;
        }
        // DELETE
        function deletePublicacao(){
            $sqlQuery = "DELETE FROM " . $this->db_table . " WHERE IDPub = ?";
            $stmt = $this->conn->prepare($sqlQuery);
        
            $this->idpub=htmlspecialchars(strip_tags($this->idpub));
        
            $stmt->bindParam(1, $this->idpub);
        
            if($stmt->execute()){
                return true;
            }
            return false;
        }
    }
