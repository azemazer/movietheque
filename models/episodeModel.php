<?php 

class Show {
    private int $id;
    private string $title;
    private int $length;
    private DateTime $airing_date;
    public function 
    __construct(Object $request) {
        $this->id = $request->id ?? null;
        $this->title = $request->title;
        $this->length = $request->length;
        $this->airing_date = $request->airing_date;
    }
    public function getTitle()
    {
        return $this->title;
    }
    public function setTitle(string $title)
    {
        $this->title = $title;
    }
    public function getLength()
    {
        return $this->length;
    }
    public function setLength(int $length)
    {
        $this->length = $length;
    }
    public function getAiringDate()
    {
        return $this->airing_date;
    }
    public function setAiringDate(DateTime $airing_date)
    {
        $this->airing_date = $airing_date;
    }
}