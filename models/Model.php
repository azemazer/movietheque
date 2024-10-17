<?php

class Model {
    private string $table;
    private int $id;

    public static function create(){

    }

    public function update(){

    }

    public function delete(){
        // PDO
        try {
            $dbh = new PDO('mysql:host=localhost;dbname=movietheque', "root", "root");

        } catch( PDOException $e ){
        }

        $sql = "DELETE FROM ".$this->table." WHERE id = ".strval($this->id);
        $conn = $dbh->prepare($sql);
        $conn->execute();
        $data = $conn->fetchAll();

        return $data;
    }

    public static function staticDelete($id){

    }
}