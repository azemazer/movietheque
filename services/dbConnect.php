<?php

class dbConnect {

    protected static $pdo;

    public static function getConnection(): PDO{
        if (!self::$pdo){
            self::$pdo = new PDO('mysql:host=localhost;dbname=movietheque', "root", "");
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            printf("PDO created.");
        }
        return self::$pdo;
    }

    public static function sqlPost($sql, $args = []){
        $res = null;
        // PDO
        try {
            // Request
            $conn = self::getConnection()->prepare($sql);
            $res = count($args) > 0 ?  $conn->execute($args) : $conn->execute();
            return $res;
        } catch( PDOException $e ){
            //throw $e;
            var_dump($e);
            exit();
        }
        return $res;
    }

    public static function sqlGet($sql): PDOStatement{
        $conn = null;
        // PDO
        try {
            // Request
            $conn = self::getConnection()->query($sql);
            return $conn;
        } catch( PDOException $e ){
            var_dump($e);
            exit();
        }
        return $conn;
    }

    public static function mongodb(){

    }
}