<?php
interface IFilmmakerRepository
{
    public function getFilmmakers();

    public function getFilmmakerById($filmmaker_id);

    public function createFilmmaker(string $name, string $biography); 

    public function updateFilmmaker($filmmaker_id, string $name, string $biography); 

    public function deleteFilmmaker($filmmaker_id);
}
