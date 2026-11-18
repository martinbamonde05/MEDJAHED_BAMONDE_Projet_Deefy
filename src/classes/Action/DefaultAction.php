<?php
declare(strict_types=1);

namespace deefy\classes\Action;

class DefaultAction extends Action {
    public function execute(): string {
        return "<h2>Bienvenue sur Deefy</h2><p>Utilisez le menu ci-dessus pour gérer vos playlists.</p>";
    }
}