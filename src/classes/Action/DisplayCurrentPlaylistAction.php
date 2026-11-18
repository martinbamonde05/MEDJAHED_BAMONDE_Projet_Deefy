<?php
declare(strict_types=1);

namespace deefy\classes\Action;

use deefy\classes\Repository\DeefyRepository;
use deefy\classes\Renderer\AudioListRenderer;

class DisplayCurrentPlaylistAction extends Action {
	
    public function execute(): string {
        if (!isset($_SESSION['user'])) {
            return "<p class='error'>Accès refusé. Veuillez vous authentifier.</p>";
        }
        if (!isset($_SESSION['current_playlist_id'])) {
            return "<p>Aucune playlist courante en session. <a href='?action=my-playlists'>Sélectionnez-en une</a>.</p>";
        }
        $plId = (int)$_SESSION['current_playlist_id'];
        $repo = DeefyRepository::getInstance();
        if (!$repo->isPlaylistOwner($_SESSION['user']['id'], $plId)) {
            return "<p class='error'>Accès refusé : vous n'êtes pas propriétaire de cette playlist.</p>";
        }
        $playlist = $repo->findPlaylistById($plId);
        if (!$playlist) {
            return "<p class='error'>La playlist courante n'existe plus.</p>";
        }
        $renderer = new AudioListRenderer($playlist);
        return $renderer->render();
    }
}