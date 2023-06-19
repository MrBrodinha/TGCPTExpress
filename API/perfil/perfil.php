<?php
    class Perfil{
        // Connection
        private $conn;
        // Table
        private $db_table = "perfil";
        // Columns
        public $idperfil;
        public $nickname;
        public $email;
        public $pass;
        public $companhia;
        public $imagem;
        public $perms;

        // Db connection
        public function __construct($db){
            $this->conn = $db;
        }

        // GET ALL
        public function getPerfil(){
            $sqlQuery = "SELECT IDPerfil, Nickname, Email, Pass, Companhia, Imagem, Perms FROM " . $this->db_table . "";
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute();
            return $stmt;
        }
        // CREATE
        public function createPerfil()
        {
            $sqlQuery = "INSERT INTO " . $this->db_table . " SET
                Nickname = :nickname,
                Email = :email,
                Pass = :pass,
                Companhia = :companhia,
                Imagem = :imagem,
                Perms = :perms";

            $stmt = $this->conn->prepare($sqlQuery);

            // sanitize
            $this->nickname = htmlspecialchars(strip_tags($this->nickname));
            $this->email = htmlspecialchars(strip_tags($this->email));
            $this->pass = htmlspecialchars(strip_tags($this->pass));
            $this->companhia = htmlspecialchars(strip_tags($this->companhia));
            $this->perms = htmlspecialchars(strip_tags($this->perms));
            $this->imagem = htmlspecialchars(strip_tags($this->imagem));

            // bind data
            $stmt->bindParam(":nickname", $this->nickname);
            $stmt->bindParam(":email", $this->email);
            $stmt->bindParam(":pass", $this->pass);
            $stmt->bindParam(":companhia", $this->companhia);
            $stmt->bindParam(":imagem", $this->imagem);
            $stmt->bindParam(":perms",$this->perms);

            if ($stmt->execute()) {
                return true;
            }
            return false;
        }

        // READ single
        public function getSinglePerfil(){
            $sqlQuery = "SELECT
                        IDPerfil, 
                        Nickname, 
                        Email, 
                        Pass, 
                        Companhia,
                        Imagem, 
                        Perms
                      FROM
                        ". $this->db_table ."
                    WHERE 
                       IDPerfil = ?
                    LIMIT 0,1";

            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->bindParam(1, $this->idperfil);
            $stmt->execute();
            $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($dataRow) {
                $this->nickname = $dataRow['Nickname'];
                $this->email = $dataRow['Email'];
                $this->pass = $dataRow['Pass'];
                $this->companhia = $dataRow['Companhia'];
                $this->imagem = $dataRow['Imagem'];
                $this->perms = $dataRow['Perms'];
            }
        }        
        // UPDATE
        public function updatePerfil(){
            $sqlQuery = "UPDATE
                        ". $this->db_table ."
                    SET
                        Nickname = :nick,
                        Email = :email,
                        Pass = :pass,
                        Companhia = :companhia,
                        Imagem = :imagem,
                        Perms = :perms
                    WHERE 
                        idperfil = :idperfil";
        
            $stmt = $this->conn->prepare($sqlQuery);
        
            $this->nickname=htmlspecialchars(strip_tags($this->nickname));
            $this->email=htmlspecialchars(strip_tags($this->email));
            $this->pass=htmlspecialchars(strip_tags($this->pass));
            $this->companhia=htmlspecialchars(strip_tags($this->companhia));
            $this->imagem=htmlspecialchars(strip_tags($this->imagem));
            $this->perms=htmlspecialchars(strip_tags($this->perms));
            $this->idperfil=htmlspecialchars(strip_tags($this->idperfil));
        
            // bind data
            $stmt->bindParam(":nick", $this->nickname);
            $stmt->bindParam(":email", $this->email);
            $stmt->bindParam(":pass", $this->pass);
            $stmt->bindParam(":companhia", $this->companhia);
            $stmt->bindParam(":imagem", $this->imagem);
            $stmt->bindParam(":perms", $this->perms);
            $stmt->bindParam(":idperfil", $this->idperfil);
            
            if($stmt->execute()){
               return true;
            }
            return false;
        }
        // DELETE
        function deletePerfil(){
            $sqlQuery = "DELETE FROM " . $this->db_table . " WHERE IDPerfil = ?";
            $stmt = $this->conn->prepare($sqlQuery);
        
            $this->idperfil=htmlspecialchars(strip_tags($this->idperfil));
        
            $stmt->bindParam(1, $this->idperfil);
        
            if($stmt->execute()){
                return true;
            }
            return false;
        }
    }
