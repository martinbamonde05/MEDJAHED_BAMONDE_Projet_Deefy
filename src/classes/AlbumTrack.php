<?php

require_once 'AudioTrack.php';

class AlbumTrack extends AudioTrack {
    protected string $album;
    protected int $nbpiste;

    public function __construct(string $titre, string $chemin, string $album, int $nbpiste) {
        parent::__construct($titre, $chemin);
        $this->album = $album;
        $this->nbpiste = $nbpiste;
    }
	
}