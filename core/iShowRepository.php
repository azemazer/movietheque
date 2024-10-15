<?php
interface IShowRepository
{
    public function getShows($filmmaker, $studio, $actor, $channel);

    public function getShowById($show_id);

    public function createShow(string $title, string $description, string $year, string $genre, $filmmaker, array $actors, array $studios, string $channel, int $nb_seasons, array $episodes); 

    public function updateShow($show_id, string $title, string $description, string $year, string $genre, $filmmaker, array $actors, array $studios, string $channel, int $nb_seasons, array $episodes); 

    public function deleteShow($show_id);
}
