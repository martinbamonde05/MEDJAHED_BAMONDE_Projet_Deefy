<?php
declare(strict_types=1);

namespace deefy\classes;

class Playlist extends AudioList {
    private ?int $id = null;

    public function __construct(string $nom, array $pistes = []) {
        parent::__construct($nom, $pistes);
    }

    public function setID(int $id): void {
        $this->id = $id;
    }

    public function addTrack(AudioTrack $track): void {
        $this->pistes[] = $track;
        $this->nbPistes = count($this->pistes);
        $this->dureeTotale += $track->duree;
    }

    public function __get(string $at): mixed {
        if (property_exists($this, $at)) {
            return $this->$at;
        }
        return parent::__get($at);
    }
}