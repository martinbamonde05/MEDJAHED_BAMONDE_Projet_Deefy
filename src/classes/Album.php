<?php
require_once 'AudioList.php';

class Album extends AudioList {
    protected string $artiste;
    protected string $dateSortie;

    public function __construct(string $n, array $p) {
        parent::__construct($n, $p);
    }

    public function setArtiste(string $a): void {
        $this->artiste = $a;
    }

    public function setDateSortie(string $dsortie): void {
        $this->dateSortie = $dsortie;
    }
}