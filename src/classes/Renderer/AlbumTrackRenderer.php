<?php

namespace iutnc\deefy\render;

require_once 'AudioTrackRenderer.php';

class AlbumTrackRenderer extends AudioTrackRenderer {
    protected function renderCompact(): string {
        // Affichage compact avec balise audio html[cite: 9]
        return "<div>{$this->track->titre} <audio src='{$this->track->chemin}' controls></audio></div>";
    }

    protected function renderLong(): string {
        $albumName = ($this->track instanceof AlbumTrack) ? $this->track->album : "Inconnu";
        return "<div>
                    <h2>{$this->track->titre}</h2>
                    <p>Album : {$albumName}</p>
                    <audio src='{$this->track->chemin}' controls></audio>
                </div>";
    }
}