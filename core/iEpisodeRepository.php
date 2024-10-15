<?php
interface IEpisodeRepository
{
    public function getEpisodes($filmmaker, $studio, $actor);

    public function getEpisodeById($book_id);

    public function createEpisode(string $title, string $description, string $year, string $genre, array $filmmakers, array $actors, array $studios); 

    public function updateEpisode($book_id, string $title, string $description, string $year, string $genre, array $filmmakers, array $actors, array $studios); 

    public function deleteEpisode($book_id);
}
