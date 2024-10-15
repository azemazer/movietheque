<?php 

class Show {
    private int $id;
    private string $title;
    private string $description;
    private string $year;
    private string $genre;
    private array $filmmaker;
    private array $actors;
    private array $studios;
    private string $channel;
    private int $nb_seasons;
    private array $episodes;
    public function __construct(Object $request) {
        $this->id = $request->id ?? null;
        $this->title = $request->title;
        $this->description = $request->description;
        $this->year = $request->year;
        $this->genre = $request->genre;
        $this->filmmaker = $request->filmmaker;
        $this->actors = $request->actors;
        $this->studios = $request->studios;
        $this->channel = $request->channel;
        $this->nb_seasons = $request->nb_seasons;
        $this->episodes = $request->episodes;
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
        return $this->filmmaker;
    }
    public function setFilmmaker(Filmmaker $filmmaker)
    {
        $this->filmmaker = $filmmaker;
    }
    public function getActors()
    {
        return $this->actors;
    }
    public function setActors(array $actors)
    {
        $this->actors = $actors;
    }
    public function getChannel()
    {
        return $this->channel;
    }
    public function setChannel(array $channel)
    {
        $this->channel = $channel;
    }
    public function getNbSeasons()
    {
        return $this->nb_seasons;
    }
    public function setNbSeasons(array $nb_seasons)
    {
        $this->nb_seasons = $nb_seasons;
    }
    public function getEpisodes()
    {
        return $this->episodes;
    }
    public function setEpisodes(array $episodes)
    {
        $this->episodes = $episodes;
    }
}