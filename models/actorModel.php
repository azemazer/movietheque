<?php 

class Actor {
    private int $id;
    private string $name;
    private string $biography;

    public function __construct(Object $request) {
        $this->id = $request->id ?? null;
        $this->name = $request->name;
        $this->biography = $request->biography;
    }
    public function getName()
    {
        return $this->name;
    }
    public function setName(string $name)
    {
        $this->name = $name;
    }
    public function getBiography()
    {
        return $this->biography;
    }
    public function setBiography(string $biography)
    {
        $this->biography = $biography;
    }
}