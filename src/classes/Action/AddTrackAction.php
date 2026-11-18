<?php
declare(strict_types=1);

namespace deefy\classes\Action;

use deefy\classes\Repository\DeefyRepository;
use deefy\classes\Renderer\AudioListRenderer;

class AddTrackAction extends Action {
	
	
    public function execute(): string {
        if (!isset($_SESSION['user'])) {
            return "<p class='error'>Accès refusé. Veuillez vous authentifier.</p>";
        }

        if (!isset($_SESSION['current_playlist_id'])) {
            return "<p class='error'>Aucune playlist courante sélectionnée.</p>";
        }

        $plId = (int)$_SESSION['current_playlist_id'];
        $repo = DeefyRepository::getInstance();
        if (!$repo->isPlaylistOwner($_SESSION['user']['id'], $plId)) {
            return "<p class='error'>Accès refusé : vous n'êtes pas propriétaire de cette playlist.</p>";
        }

        if ($this->http_method === 'POST') {
            $titre = trim(filter_var($_POST['titre'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS));
            $genre = trim(filter_var($_POST['genre'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS));
            $duree = filter_var($_POST['duree'] ?? 0, FILTER_VALIDATE_INT);
            $artiste = trim(filter_var($_POST['artiste'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS));
            $album = trim(filter_var($_POST['album'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS));

            if (empty($titre) || $duree === false || $duree < 0) {
                return "<p class='error'>Titre obligatoire et durée valide requise.</p>";
            }

            $repo->addTrackToPlaylist($plId, $titre, $genre, $duree, $artiste, $album);
            $playlist = $repo->findPlaylistById($plId);

            $renderer = new AudioListRenderer($playlist);
            return "<p class='success'>Piste ajoutée !</p>" . $renderer->render();
        }

        return "<h2>Ajouter une piste à la playlist courante</h2>
        <form method='POST' action='?action=add-track'>
            <label>Titre : <input type='text' name='titre' required></label>
            <label>Genre : <input type='text' name='genre'></label>
            <label>Durée (secondes) : <input type='number' name='duree' min='1' required></label>
            <label>Artiste : <input type='text' name='artiste'></label>
            <label>Album : <input type='text' name='album'></label>
            <button type='submit'>Ajouter la piste</button>
        </form>";
    }
}