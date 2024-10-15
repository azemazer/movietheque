<?php 

class Studio {
    private int $id;
    private string $name;

    public function __construct(Object $request) {
        $this->id = $request->id ?? null;
        $this->name = $request->name;
    }
    public function getId(){
        return $this->id;
    }
    public function getName()
    {
        return $this->name;
    }
    public function setName(string $name)
    {
        $this->name = $name;
    }
}