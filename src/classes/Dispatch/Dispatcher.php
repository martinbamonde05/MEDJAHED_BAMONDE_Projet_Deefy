<?php
declare(strict_types=1);

namespace deefy\classes\Dispatch;

use deefy\classes\Action\DefaultAction;
use deefy\classes\Action\MesPlaylistsAction;
use deefy\classes\Action\DisplayPlaylistAction;
use deefy\classes\Action\AddPlaylistAction;
use deefy\classes\Action\DisplayCurrentPlaylistAction;
use deefy\classes\Action\AddTrackAction;
use deefy\classes\Action\RegisterAction;
use deefy\classes\Action\SigninAction;
use deefy\classes\Action\LogoutAction;

class Dispatcher {
    private string $action;

    public function __construct() {
        $this->action = $_GET['action'] ?? 'default';
    }

    public function run(): void {
        $actionInstance = match ($this->action) {
            'my-playlists'    => new MesPlaylistsAction(),
            'display-pl'      => new DisplayPlaylistAction(),
            'add-playlist'    => new AddPlaylistAction(),
            'current-pl'      => new DisplayCurrentPlaylistAction(),
            'add-track'       => new AddTrackAction(),
            'register'        => new RegisterAction(),
            'signin'          => new SigninAction(),
            'logout'          => new LogoutAction(),
            default           => new DefaultAction(),
        };

        $html = $actionInstance->execute();
        $this->renderPage($html);
    }

    private function renderPage(string $html): void {
        $isConnected = isset($_SESSION['user']);
        $userEmail = $isConnected ? htmlspecialchars($_SESSION['user']['email'], ENT_QUOTES, 'UTF-8') : '';

        echo "<!DOCTYPE html>
<html lang='fr'>
<head>
    <meta charset='UTF-8'>
    <title>Deefy</title>
    <style>
        body { font-family: sans-serif; max-width: 800px; margin: 20px auto; padding: 0 15px; }
        nav { background: #222; padding: 10px; margin-bottom: 20px; }
        nav a { color: #fff; margin-right: 15px; text-decoration: none; }
        nav a:hover { text-decoration: underline; }
        form label { display: block; margin-top: 10px; }
        button { margin-top: 15px; padding: 8px 15px; cursor: pointer; }
        .error { color: #c00; font-weight: bold; }
        .success { color: #080; font-weight: bold; }
    </style>
</head>
<body>
    <header>
        <h1>Deefy</h1>
        <nav>
            <a href='?action=default'>Accueil</a>
            <a href='?action=my-playlists'>Mes playlists</a>
            <a href='?action=add-playlist'>Créer une playlist</a>
            <a href='?action=current-pl'>Playlist courante</a>";
        
        if ($isConnected) {
            echo " <a href='?action=logout'>Déconnexion ({$userEmail})</a>";
        } else {
            echo " <a href='?action=signin'>S'authentifier</a>
                   <a href='?action=register'>S'inscrire</a>";
        }

        echo "
        </nav>
    </header>
    <main>
        {$html}
    </main>
</body>
</html>";
    }
}