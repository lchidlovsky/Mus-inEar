<?php
namespace Models\Classes;

class Miniature{
    protected int $id;
    protected string $nom;
    protected string $lienImg;
    protected string $href;

    public function __construct(int $id, string $nom, string $lienImg, string $redirection){
        $this->id = $id;
        $this->nom = $nom;
        $this->lienImg = $lienImg;
        $this->href = $redirection;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function __toString(){
        return "<a href='$this->href' class='album'><img src=$this->lienImg><h2>$this->nom</h2></a>";
    }
}

?>
