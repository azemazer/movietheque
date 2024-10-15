<?php 

class Movie {
    private int $id;
    private string $title;
    private string $description;
    private string $year;
    private string $genre;
    private array $filmmakers;
    private array $actors;
    private array $studios;
    public function __construct(Object $request) {
        $this->id = $request->id ?? null;
        $this->title = $request->title;
        $this->description = $request->description;
        $this->year = $request->year;
        $this->genre = $request->genre;
        $this->filmmakers = $request->filmmakers;
        $this->actors = $request->actors;
        $this->studios = $request->studios;
    }
    public function getId()
    {
        return $this->id;
    }
    public function getTitle()
    {
        return $this->title;
    }
    public function setTitle(string $title)
    {
        $this->title = $title;
    }
    public function getDescription()
    {
        return $this->description;
    }
    public function setDescription(string $description)
    {
        $this->description = $description;
    }
    public function getYear()
    {
        return $this->year;
    }
    public function setYear(string $year)
    {
        $this->year = $year;
    }
    public function getGenre()
    {
        return $this->genre;
    }
    public function setGenre(string $genre)
    {
        $this->genre = $genre;
    }
    public function getFilmmaker()
    {
        return $this->filmmakers;
    }
    public function setFilmmaker(Filmmaker $filmmakers)
    {
        $this->filmmakers = $filmmakers;
    }
    public function getActors()
    {
        return $this->actors;
    }
    public function setActors(array $actors)
    {
        $this->actors = $actors;
    }
    public function getStudios()
    {
        return $this->studios;
    }
    public function setStudios(array $studios)
    {
        $this->studios = $studios;
    }
}