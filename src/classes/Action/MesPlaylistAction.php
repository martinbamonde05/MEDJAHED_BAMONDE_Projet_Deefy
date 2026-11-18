<?php
declare(strict_types=1);

namespace deefy\classes\Action;

use deefy\classes\Repository\DeefyRepository;

class MesPlaylistsAction extends Action {
    public function execute(): string {
        // Autorisation 1 : Utilisateur authentifié
        if (!isset($_SESSION['user'])) {
            return "<p class='error'>Accès refusé. Veuillez vous authentifier.</p>";
        }

        $repo = DeefyRepository::getInstance();
        $playlists = $repo->findPlaylistsByUser($_SESSION['user']['id']);

        if (empty($playlists)) {
            return "<h2>Mes playlists</h2><p>Vous n'avez aucune playlist. <a href='?action=add-playlist'>En créer une</a>.</p>";
        }

        $html = "<h2>Mes playlists</h2><ul>";
        foreach ($playlists as $pl) {
            $nom = htmlspecialchars($pl->nom, ENT_QUOTES, 'UTF-8');
            $html .= "<li><a href='?action=display-pl&id={$pl->id}'>{$nom}</a></li>";
        }
        $html .= "</ul>";

        return $html;
    }
}