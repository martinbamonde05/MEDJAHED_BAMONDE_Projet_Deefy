<?php
declare(strict_types=1);

namespace deefy\classes\Action;

use deefy\classes\Repository\DeefyRepository;
use deefy\classes\Renderer\AudioListRenderer;

class DisplayPlaylistAction extends Action {
    public function execute(): string {
        if (!isset($_SESSION['user'])) {
            return "<p class='error'>Accès refusé. Veuillez vous authentifier.</p>";
        }

        $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
        if (!$id) {
            return "<p class='error'>Identifiant de playlist invalide.</p>";
        }
        $repo = DeefyRepository::getInstance();
        if (!$repo->isPlaylistOwner($_SESSION['user']['id'], $id)) {
            return "<p class='error'>Accès refusé : vous n'êtes pas propriétaire de cette playlist.</p>";
        }
        $playlist = $repo->findPlaylistById($id);
        if (!$playlist) {
            return "<p class='error'>Playlist introuvable.</p>";
        }
        $_SESSION['current_playlist_id'] = $playlist->id;

        $renderer = new AudioListRenderer($playlist);
        return $renderer->render();
    }
}