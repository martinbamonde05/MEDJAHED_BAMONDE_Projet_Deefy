<?php

require_once 'AudioTrack.php';

class PodcastTrack extends AudioTrack {
    public function __construct(string $titre, string $chemin) {
        parent::__construct($titre, $chemin);
    }

}