<?php
interface IActorRepository
{
    public function getActors();

    public function getActorById($actor_id);

    public function createActor(string $name, string $biography); 

    public function updateActor($actor_id, string $name, string $biography); 

    public function deleteActor($actor_id);
}
