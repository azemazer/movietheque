<?php

use IMovieRepository as GlobalIMovieRepository;

require_once "../core/iMovieRepository.php";
require_once "../models/movieModel.php";
require_once "../models/filmmakerModel.php";
require_once "../models/actorModel.php";

class iMovieRepository implements GlobalIMovieRepository
{
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
        JOIN movie_author ma ON ma.idMovie = m.id
        JOIN author a ON ma.idAuthor = a.id
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
            JOIN movie_author ma ON ma.idMovie = m.id
            JOIN author a ON ma.idAuthor = a.id
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

    public function getFormattedMovies($filmmaker, $studio, $actor){

        // PDO
        try {
            $dbh = new PDO('mysql:host=localhost;dbname=movietheque', "root", "root");

        } catch( PDOException $e ){
        }

        // Request
        $sql = 'SELECT m.id AS movie_id,
        m.title AS movie_title,
        m.year AS movie_year,
        m.genre AS movie_genre,
        GROUP_CONCAT(`a.name` SEPARATOR ", ") AS authors,
        GROUP_CONCAT(`f.name` SEPARATOR ", ") AS filmmakers,
        GROUP_CONCAT(`s.name` SEPARATOR ", ") AS studios,
        FROM movie m
        JOIN movie_author ma ON ma.idMovie = m.id
        JOIN author a ON ma.idAuthor = a.id
        JOIN movie_filmmaker mf ON mf.idMovie = m.id
        JOIN filmmaker f ON mf.idFilmmaker = f.id
        JOIN movie_studio ms ON ms.idMovie = m.id
        JOIN studio s ON ms.idStudio = s.id
        GROUP BY `m`.`id`
        ';
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

        return $data;
    }

    public function getMovieById($movie_id){

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
        a.name AS actor_name,
        a.biography AS actor_biography,
        a.id AS actor_id
        f.name AS filmmaker_name,
        f.biography AS filmmaker_biography,
        f.id AS filmmaker_id
        s.name AS studio_name
        FROM movie m
        JOIN movie_author ma ON ma.idMovie = m.id
        JOIN author a ON ma.idAuthor = a.id
        JOIN movie_filmmaker mf ON mf.idMovie = m.id
        JOIN filmmaker f ON mf.idFilmmaker = f.id
        JOIN movie_studio ms ON ms.idMovie = m.id
        JOIN studio s ON ms.idStudio = s.id
        WHERE m.id = " . strval($movie_id);

        $conn = $dbh->prepare($sql);
        $conn->execute();
        $data = $conn->fetchAll();
        $item = $data[0];

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
            
        return $movie;
    }
    public function createMovie(string $title, string $description, string $year, string $genre, array $filmmakers, array $actors, array $studios){
        $movie = new Movie((object)[
            "title" => $title,
            "description" => $description,
            "year" => $year,
            "genre" => $genre,
            "filmmakers" => $filmmakers,
            "actors" => $actors,
            "studios" => $studios,
        ]);
        $movie = $movie->create();
        return $movie;
    }
    public function updateMovie($movie_id, string $title, string $description, string $year, string $genre, array $filmmakers, array $actors, array $studios){
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

        $movie = $movie->update();
        return $movie;
    }
    public function deleteMovie($movie_id){
        $movie = Movie::delete($movie_id);
        return $movie;
    }
}