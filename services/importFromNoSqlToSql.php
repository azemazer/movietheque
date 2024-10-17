<?php
require_once "../vendor/autoload.php";

use Exception;
use MongoDB\Client;
use MongoDB\Driver\ServerApi;

function importFromNoSqlToSql(){
    $uri = 'mongodb://localhost:27017/';
    // Specify Stable API version 1
    $apiVersion = new ServerApi(ServerApi::V1);
    // Create a new client and connect to the server
    $client = new MongoDB\Client($uri, [], ['serverApi' => $apiVersion]);
    $movies = $client->selectDatabase('sample_mflix')->selectCollection('movies')->find([], [
        'limit' => 100
    ]);
    foreach($movies as $movie){
        $movie_data = (array)$movie;
        $actors_id = [];
        $filmmakers_id = [];
        // $studios_id = [];

        // We create / recuperate actors
        foreach($movie_data["cast"] as $actor){
            $sql = "SELECT id FROM actor WHERE name = '" . addslashes($actor) . "'";
            $conn = dbConnect::sqlGet($sql);
            $actor_id = $conn->fetchColumn();
            if (!$actor_id){
                // We create the actor since they doesn't exist
                $sql = "INSERT INTO actor (name, biography)
                VALUES (?, 'Lorem Ipsum');";
                echo $sql;
                echo '<br>';
                $res = dbConnect::sqlPost($sql, [$actor]); // Ex de remplissage des valeurs avec PDO
                $actor_id = dbConnect::getConnection()->lastInsertId();
            }
            array_push($actors_id, $actor_id);
        }
        
        // We create / recuperate filmmakers
        foreach($movie_data["directors"] as $filmmaker){
            $sql = "SELECT id FROM filmmaker WHERE name = '" . addslashes($filmmaker) . "'";
            $conn = dbConnect::sqlGet($sql);
            $filmmaker_id = $conn->fetchColumn();
            if (!$filmmaker_id){
                // We create the filmmaker since they doesn't exist
                $sql = "INSERT INTO filmmaker (name, biography)
                VALUES ('".addslashes($filmmaker)."', 'Lorem Ipsum');";
                $res = dbConnect::sqlPost($sql);
                $filmmaker_id = dbConnect::getConnection()->lastInsertId();
            }
            array_push($filmmakers_id, $filmmaker_id);
        }

        // We create / recuperate studios
        // Actually there are no studios in the base DB so we don't

        // We create the movie
        $genres = join(", ", (array)$movie_data["genres"]);
        $date = isset($movie_data["released"]) ? $movie_data["released"]->toDateTime() : null;
        $year = $date ? $date->format('Y') : null;
        $sql = "INSERT INTO movie (title, description, year, genre)
        VALUES ( :name, :description, :year, :genre)";
        $args = [
            "name" => $movie_data["title"],
            "description" => $movie_data["plot"],
            "year" => $year,
            "genre" => $genres
        ];
        //var_dump($sql, $args);
        //exit();
        $res = dbConnect::sqlPost($sql, $args);
        $movie_id = dbConnect::getConnection()->lastInsertId();

        // We create the links between the movie and actors/filmmakers
        $pivot_sql = "";
        foreach($actors_id as $id_actor){
            $pivot_sql .= "INSERT INTO movie_actor (idMovie, idActor)
            VALUES (".$movie_id.", ".$id_actor.");";
        }
        foreach($filmmakers_id as $id_filmmaker){
            $pivot_sql .= "INSERT INTO movie_filmmaker (idMovie, idFilmmaker)
            VALUES (".$movie_id.", ".$id_filmmaker.");";
        }
        $pivot_data = dbConnect::sqlPost($pivot_sql);
        if($pivot_data){
            echo "<strong>Succés!</strong> Les données ont été transférées en SQL avec succès.";
        } else {
            echo "<strong>Echec.</strong> Echec du transfert de données.";
        }
    }
}