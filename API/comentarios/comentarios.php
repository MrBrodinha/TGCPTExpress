<?php
    class Comentarios{
        // Connection
        private $conn;
        // Table
        private $db_table = "comentarios";
        // Columns
        public $idperfil;
        public $idpub;
        public $comentario;
        public $idcom;

        // Db connection
        public function __construct($db){
            $this->conn = $db;
        }

        // GET ALL
        public function getComentarios(){
            $sqlQuery = "SELECT IDCom, IDPerfil, IDPub, Comentario FROM " . $this->db_table . "";
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute();
            return $stmt;
        }
        // CREATE
        public function createComentarios()
        {
            $sqlQuery = "INSERT INTO " . $this->db_table . " SET
                        IDPerfil = :idperfil, 
                        IDPub = :idpub, 
                        Comentario = :comentario";
            $stmt = $this->conn->prepare($sqlQuery);

            // sanitize
            $this->idperfil = htmlspecialchars(strip_tags($this->idperfil));
            $this->idpub = htmlspecialchars(strip_tags($this->idpub));
            $this->comentario = htmlspecialchars(strip_tags($this->comentario));

            // bind data
            $stmt->bindParam(":idperfil", $this->idperfil);
            $stmt->bindParam(":idpub", $this->idpub);
            $stmt->bindParam(":comentario", $this->comentario);


            if ($stmt->execute()) {
                return true;
            }
            return false;
        }

        // READ single
        public function getSingleComentarios($idpub){
            $sqlQuery = "SELECT
                            IDPub, 
                            IDPerfil, 
                            IDCom,
                            Comentario
                        FROM
                            ". $this->db_table ."
                        WHERE 
                            IDPub = ".$idpub;

            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute();
            return $stmt;
        }

        // UPDATE
        public function updateComentarios(){
            $sqlQuery = "UPDATE
                        ". $this->db_table ."
                    SET
                        Comentario := :comentario
                    WHERE 
                        idperfil = :idperfil
                    AND
                        idpub = :idpub";
        
            $stmt = $this->conn->prepare($sqlQuery);
        
            $this->idperfil=htmlspecialchars(strip_tags($this->idperfil));
            $this->idpub=htmlspecialchars(strip_tags($this->idpub));
            $this->comentario=htmlspecialchars(strip_tags($this->comentario));
        
            // bind data
            $stmt->bindParam(":idperfil", $this->idperfil);
            $stmt->bindParam(":idpub", $this->idpub);
            $stmt->bindParam(":comentario", $this->comentario);
            
            if($stmt->execute()){
               return true;
            }
            return false;
        }
        // DELETE
        function deleteComentarios(){
            $sqlQuery = "DELETE FROM " . $this->db_table . " WHERE IDPerfil = ? OR IDPub = ? OR IDCom = ?";
            $stmt = $this->conn->prepare($sqlQuery);
        
            $this->idpub=htmlspecialchars(strip_tags($this->idpub));
            $this->idperfil=htmlspecialchars(strip_tags($this->idperfil));
            $this->idcom=htmlspecialchars(strip_tags($this->idcom));
        
            $stmt->bindParam(1, $this->idperfil);
            $stmt->bindParam(2, $this->idpub);
            $stmt->bindParam(3, $this->idcom);
        
            if($stmt->execute()){
                return true;
            }
            return false;
        }
    }
