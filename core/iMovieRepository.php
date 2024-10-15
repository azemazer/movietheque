<?php
interface IMovieRepository
{
    public function getMovies($filmmaker, $studio, $actor);

    public function getMovieById($movie_id);

    public function createMovie(string $title, string $description, string $year, string $genre, array $filmmakers, array $actors, array $studios); 

    public function updateMovie($movie_id, string $title, string $description, string $year, string $genre, array $filmmakers, array $actors, array $studios); 

    public function deleteMovie($movie_id);
}
