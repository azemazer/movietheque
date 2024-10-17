<?php

require_once "../core/iMovieRepository.php";
require_once "../models/movieModel.php";
require_once "../models/filmmakerModel.php";
require_once "../models/actorModel.php";
require_once "../models/studioModel.php";
require_once "../services/dbConnect.php";

class MovieRepository implements IMovieRepository
{

    private $driver = "sql";

    public function __construct()
    {
        $this->driver = isset($_GET["driver"]) && $_GET["driver"] === "mongo" ? "mongo" : "sql";
    }

    public function getMovies($filmmaker, $studio, $actor){
        // Fonction pas finie car pas sûr d'en avoir besoin finalement

        // PDO
        try {
            $dbh = new PDO('mysql:host=localhost;dbname=movietheque', "root", "root");

        } catch( PDOException $e ){
        }

        // Request
        $sql = "SELECT m.id AS movie_id,
        m.title AS movie_title,
        m.year AS movie_year,
        m.genre AS movie_genre,
        a.id AS actor_id
        a.id AS actor_id
        f.id AS filmmaker_id
        FROM movie m
        JOIN movie_actor ma ON ma.idMovie = m.id
        JOIN actor a ON ma.idAuthor = a.id
        JOIN movie_filmmaker mf ON mf.idMovie = m.id
        JOIN filmmaker f ON mf.idFilmmaker = f.id
        JOIN movie_studio ms ON ms.idMovie = m.id
        JOIN studio s ON ms.idStudio = s.id
        ";
        $where_and = " WHERE";
        if ($filmmaker != 0){
            $sql .= " WHERE f.id = " . strval($filmmaker);
            $where_and = " AND";
        }
        if ($studio != 0){
            $sql .= $where_and . "s.id = " . strval($studio);
        }
        if ($actor != 0){
            $sql .= $where_and . "a.id = " . strval($actor);
        }
        $conn = $dbh->prepare($sql);
        $conn->execute();
        $data = $conn->fetchAll();

        // Models
        $res = [];
        foreach($data as $item){

            // Requete infos complémentaires
            $sql = "SELECT m.id AS movie_id,
            a.id AS actor_id
            a.name AS actor_name,
            a.biography AS actor_biography,
            a.id AS actor_id
            f.name AS filmmaker_name,
            f.biography AS filmmaker_biography,
            f.id AS filmmaker_id
            s.name AS studio_name
            FROM movie m
            JOIN movie_actor ma ON ma.idMovie = m.id
            JOIN actor a ON ma.idAuthor = a.id
            JOIN movie_filmmaker mf ON mf.idMovie = m.id
            JOIN filmmaker f ON mf.idFilmmaker = f.id
            JOIN movie_studio ms ON ms.idMovie = m.id
            JOIN studio s ON ms.idStudio = s.id
            WHERE m.id = " . strval($item["movie_id"]);

            $filmmaker = new Filmmaker((object)[
                "id" => $item["filmmaker_id"],
                "name" => $item["name"],
                "biography" => $item["biography"],
            ]);
            
            $actor = new Actor((object)[
                "id" => $item["actor_id"],
                "name" => $item["actor_name"],
                "biography" => $item["actor_biography"],
            ]);

            $studio = new Studio((object)[
                "id" => $item["studio_id"],
                "name" => $item["studio_name"],
            ]);

            $movie = new Movie((object)[
                "id" => $item["movie_id"],
                "title" => $item["movie_title"],
                "description" => $item["movie_description"],
                "year" => $item["movie_year"],
                "genre" => $item["movie_genre"],
                "filmmakers" => [$filmmaker],
                "actors" => [$actor],
                "studios" => [$filmmaker],
            ]);

            array_push($res, $movie);
        }
        
    }

    public function getFormattedMovies(array $filmmaker = [], array $studio = [], array $actor = [])
    {
        $data = null;
        if($this->driver === "sql"){
            
            // Request
            $sql = 'SELECT m.id AS movie_id,
            m.title AS movie_title,
            m.year AS movie_year,
            m.genre AS movie_genre,
            GROUP_CONCAT(DISTINCT a.name SEPARATOR ", ") AS actors,
            GROUP_CONCAT(DISTINCT f.name SEPARATOR ", ") AS filmmakers,
            GROUP_CONCAT(DISTINCT s.name SEPARATOR ", ") AS studios
            FROM movie m
            LEFT JOIN movie_actor ma ON ma.idMovie = m.id
            LEFT JOIN actor a ON ma.idActor = a.id
            LEFT JOIN movie_filmmaker mf ON mf.idMovie = m.id
            LEFT JOIN filmmaker f ON mf.idFilmmaker = f.id
            LEFT JOIN movie_studio ms ON ms.idMovie = m.id
            LEFT JOIN studio s ON ms.idStudio = s.id
            GROUP BY `m`.`id`
            ';
            $where_and = " WHERE";
            if (sizeof($filmmaker) > 0){
                $sql .= " WHERE f.id = " . strval($filmmaker);
                $where_and = " AND";
            }
            if (sizeof($studio) > 0){
                $sql .= $where_and . "s.id = " . strval($studio);
            }
            if (sizeof($actor) > 0){
                $sql .= $where_and . "a.id = " . strval($actor);
            }
            $conn = dbConnect::sqlGet($sql);
            $data = $conn->fetchAll();
        }
        return $data;
    }

    public function getMovieById($movie_id)
    {
        $items = null;

        if($this->driver === "sql"){
            // Request
            $sql = "SELECT m.id AS movie_id,
            m.title AS movie_title,
            m.description AS movie_description,
            m.year AS movie_year,
            m.genre AS movie_genre,
            a.id AS actor_id,
            a.name AS actor_name,
            a.biography AS actor_biography,
            a.id AS actor_id,
            f.name AS filmmaker_name,
            f.biography AS filmmaker_biography,
            f.id AS filmmaker_id,
            s.name AS studio_name
            FROM movie m
            LEFT JOIN movie_actor ma ON ma.idMovie = m.id
            LEFT JOIN actor a ON ma.idActor = a.id
            LEFT JOIN movie_filmmaker mf ON mf.idMovie = m.id
            LEFT JOIN filmmaker f ON mf.idFilmmaker = f.id
            LEFT JOIN movie_studio ms ON ms.idMovie = m.id
            LEFT JOIN studio s ON ms.idStudio = s.id
            WHERE m.id = " . strval($movie_id);
    
            $conn = dbConnect::sqlGet($sql);
            $items = $conn->fetchAll();
        }

        // Getting all actors, filmmakers and studios
        $filmmakers = [];
        $actors = [];
        $studios = [];
        foreach($items as $item){
            // Check if filmmaker already exists
            $previous_filmmaker = array_filter($filmmakers, function($old_filmmaker) use ($item) {
                return $old_filmmaker->getId() === $item['filmmaker_id'];
            });
            if (!$previous_filmmaker){
                $filmmaker = new Filmmaker((object)[
                    "id" => $item["filmmaker_id"],
                    "name" => $item["filmmaker_name"],
                    "biography" => $item["filmmaker_biography"],
                ]);
                array_push($filmmakers, $filmmaker);
            }
    
            // Check if actor already exists
            $previous_actor = array_filter($actors, function($old_actor) use ($item) {
                return $old_actor->getId() === $item['actor_id'];
            });
            if (!$previous_actor){
                $actor = new Actor((object)[
                    "id" => $item["actor_id"],
                    "name" => $item["actor_name"],
                    "biography" => $item["actor_biography"],
                ]);
                array_push($actors, $actor);
            }
    
            // Check if studio already exists
            $previous_studio = array_filter($studios, function($old_studio) use ($item) {
                return $old_studio->getId() === $item['studio_id'];
            });
            if (!$previous_studio && isset($item["studio_id"])){
                $studio = new Studio((object)[
                    "id" => $item["studio_id"],
                    "name" => $item["studio_name"],
                ]);
                array_push($studios, $studio);
            }
        }

        $movie = $items[0];

        $movie = new Movie((object)[
            "id" => $movie["movie_id"],
            "title" => $movie["movie_title"],
            "description" => $movie["movie_description"],
            "year" => $movie["movie_year"],
            "genre" => $movie["movie_genre"],
            "filmmakers" => $filmmakers,
            "actors" => $actors,
            "studios" => $studios,
        ]);
            
        return $movie;
    }
    public function createMovie(string $title, string $description, string $year, string $genre, array $filmmakers, array $actors, array $studios)
    {
        $movie_id = null;

        if($this->driver === "sql"){
            $sql = "INSERT INTO movie (title, description, year, genre)
            VALUES ('".$title."', '".$description."', ".$year.", '".$description."');
            SELECT @@IDENTITY AS LastMovieId;";
    
            $res = dbConnect::sqlPost($sql);
            $movie_id = $res[0]["LastMovieId"];
            $sql_pivot = "";
            foreach($filmmakers as $filmmaker){
                $sql_pivot .= "INSERT INTO movie_filmmaker (idMovie, idFilmmaker)
                VALUES ". $movie_id .", ".$filmmaker["id"].";";
            }
            foreach($actors as $actor){
                $sql_pivot .= "INSERT INTO movie_actor (idMovie, idActor)
                VALUES ". $movie_id .", ".$actor["id"].";";
            }
            foreach($studios as $studio){
                $sql_pivot .= "INSERT INTO movie_studio (idMovie, idStudio)
                VALUES ". $movie_id .", ".$studio["id"].";";
            }
            if($sql_pivot !== ""){
                dbConnect::sqlPost($sql);
            }
        }
        $movie = new Movie((object)[
            "id" => $movie_id,
            "title" => $title,
            "description" => $description,
            "year" => $year,
            "genre" => $genre,
            "filmmakers" => $filmmakers,
            "actors" => $actors,
            "studios" => $studios,
        ]);
        return $movie;
    }
    public function updateMovie($movie_id, string $title, string $description, string $year, string $genre, array $filmmakers, array $actors, array $studios)
    {
        $res = null;
        if($this->driver === "sql"){
            $sql = "UPDATE movie
            SET title = ".$title.", description = ".$description.", year = ".strval($year).", genre = ".$genre." 
            WHERE id = ".strval($movie_id);
            $sql .= "
            DELETE * FROM movie_filmmaker WHERE idMovie = ".strval($movie_id).";
            DELETE * FROM movie_actor WHERE idMovie = ".strval($movie_id).";
            DELETE * FROM movie_studio WHERE idMovie = ".strval($movie_id).";
            ";
            foreach($filmmakers as $filmmaker){
                $sql .= "INSERT INTO movie_filmmaker (idMovie, idFilmmaker)
                VALUES ". $movie_id .", ".$filmmaker["id"].";";
            }
            foreach($actors as $actor){
                $sql .= "INSERT INTO movie_actor (idMovie, idActor)
                VALUES ". $movie_id .", ".$actor["id"].";";
            }
            foreach($studios as $studio){
                $sql .= "INSERT INTO movie_studio (idMovie, idStudio)
                VALUES ". $movie_id .", ".$studio["id"].";";
            }
            $res = dbConnect::sqlPost($sql);
        }
        $movie = new Movie((object)[
            "id" => $movie_id,
            "title" => $title,
            "description" => $description,
            "year" => $year,
            "genre" => $genre,
            "filmmakers" => $filmmakers,
            "actors" => $actors,
            "studios" => $studios,
        ]);

        return $movie;
    }
    public function deleteMovie($movie_id){
        $sql = "DELETE * FROM movie WHERE id = ".strval($movie_id);
        $res = dbConnect::sqlPost($sql);
        return $res;
    }
}