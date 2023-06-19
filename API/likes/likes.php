<?php
    class Likes{
        // Connection
        private $conn;
        // Table
        private $db_table = "likes";
        // Columns
        public $idperfil;
        public $idpub;
        public $gosto;
        public $idlike;

        // Db connection
        public function __construct($db){
            $this->conn = $db;
        }

        // GET ALL
        public function getLikes(){
            $sqlQuery = "SELECT IDLike, IDPerfil, IDPub, Gosto FROM " . $this->db_table . "";
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute();
            return $stmt;
        }
        // CREATE
        public function createLikes()
        {
            $sqlQuery = "INSERT INTO " . $this->db_table . " SET
                        IDPerfil = :idperfil, 
                        IDPub = :idpub, 
                        Gosto = :gosto";
            $stmt = $this->conn->prepare($sqlQuery);

            // sanitize
            $this->idperfil = htmlspecialchars(strip_tags($this->idperfil));
            $this->idpub = htmlspecialchars(strip_tags($this->idpub));
            $this->gosto = htmlspecialchars(strip_tags($this->gosto));

            // bind data
            $stmt->bindParam(":idperfil", $this->idperfil);
            $stmt->bindParam(":idpub", $this->idpub);
            $stmt->bindParam(":gosto", $this->gosto);


            if ($stmt->execute()) {
                return true;
            }
            return false;
        }

        // READ single
        public function getLikesDePub($idpub)
        {
            $sqlQuery = "SELECT IDLike, IDPub, IDPerfil, Gosto FROM " . $this->db_table . " WHERE IDPub = " . $idpub;
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute();
            return $stmt;
        }     
        // UPDATE
        public function updateLikes(){
            $sqlQuery = "UPDATE
                        ". $this->db_table ."
                    SET
                        Gosto = :gosto
                    WHERE 
                        idperfil = :idperfil
                    AND
                        idpub = :idpub";
        
            $stmt = $this->conn->prepare($sqlQuery);
        
            $this->idperfil=htmlspecialchars(strip_tags($this->idperfil));
            $this->idpub=htmlspecialchars(strip_tags($this->idpub));
            $this->gosto=htmlspecialchars(strip_tags($this->gosto));
        
            // bind data
            $stmt->bindParam(":idperfil", $this->idperfil);
            $stmt->bindParam(":idpub", $this->idpub);
            $stmt->bindParam(":gosto", $this->gosto);
            
            if($stmt->execute()){
               return true;
            }
            return false;
        }
        // DELETE
        function deleteLikes(){
            $sqlQuery = "DELETE FROM " . $this->db_table . " WHERE IDPerfil = ? OR IDPub = ?";
            $stmt = $this->conn->prepare($sqlQuery);
        
            $this->idpub=htmlspecialchars(strip_tags($this->idpub));
            $this->idperfil=htmlspecialchars(strip_tags($this->idperfil));
        
            $stmt->bindParam(1, $this->idperfil);
            $stmt->bindParam(2, $this->idpub);
        
            if($stmt->execute()){
                return true;
            }
            return false;
        }
    }
