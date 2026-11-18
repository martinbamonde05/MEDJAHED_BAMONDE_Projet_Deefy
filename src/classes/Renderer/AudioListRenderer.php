<?php
declare(strict_types=1);

namespace deefy\classes\Renderer;

use deefy\classes\AudioList;

class AudioListRenderer implements Renderer {
    private AudioList $audioList;

    public function __construct(AudioList $audioList) {
        $this->audioList = $audioList;
    }

    public function render(int $selector = Renderer::COMPACT): string {
        $nom = htmlspecialchars($this->audioList->nom, ENT_QUOTES, 'UTF-8');
        $html = "<div class='playlist'>";
        $html .= "<h2>{$nom}</h2>";
        $html .= "<p>Nombre de pistes : {$this->audioList->nbPistes} | Durée totale : {$this->audioList->dureeTotale}s</p>";

        if (empty($this->audioList->pistes)) {
            $html .= "<p><em>Cette playlist ne contient aucune piste.</em></p>";
        } else {
            $html .= "<ul>";
            foreach ($this->audioList->pistes as $track) {
                $titre = htmlspecialchars($track->titre, ENT_QUOTES, 'UTF-8');
                $genre = htmlspecialchars($track->genre ?? 'Inconnu', ENT_QUOTES, 'UTF-8');
                $html .= "<li><strong>{$titre}</strong> ({$genre}) - {$track->duree}s</li>";
            }
            $html .= "</ul>";
        }
        $html .= "<p><a href='?action=add-track'>+ Ajouter une piste à cette playlist</a></p>";
        $html .= "</div>";

        return $html;
    }
}