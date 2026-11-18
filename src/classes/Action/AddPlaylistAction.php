<?php
declare(strict_types=1);

namespace deefy\classes\Action;

use deefy\classes\Playlist;
use deefy\classes\Repository\DeefyRepository;
use deefy\classes\Renderer\AudioListRenderer;

class AddPlaylistAction extends Action {
    public function execute(): string {
        if (!isset($_SESSION['user'])) {
            return "<p class='error'>Accès refusé. Veuillez vous authentifier.</p>";
        }
        if ($this->http_method === 'POST') {
            $nom = trim(filter_var($_POST['nom'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS));
            if (empty($nom)) {
                return "<p class='error'>Le nom de la playlist ne peut pas être vide.</p>";
            }

            $pl = new Playlist($nom);
            $repo = DeefyRepository::getInstance();
            $pl = $repo->saveEmptyPlaylist($pl, $_SESSION['user']['id']);
            $_SESSION['current_playlist_id'] = $pl->id;

            $renderer = new AudioListRenderer($pl);
            return "<p class='success'>Playlist créée !</p>" . $renderer->render();
        }
        return "<h2>Créer une playlist vide</h2>
        <form method='POST' action='?action=add-playlist'>
            <label>Nom de la playlist : <input type='text' name='nom' required></label>
            <button type='submit'>Créer</button>
        </form>";
    }
}