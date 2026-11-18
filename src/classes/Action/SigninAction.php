<?php
declare(strict_types=1);

namespace deefy\classes\Action;

use deefy\classes\Repository\DeefyRepository;

class SigninAction extends Action {
    public function execute(): string {
        if ($this->http_method === 'POST') {
            $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
            $pass = $_POST['password'] ?? '';

            if ($email) {
                $repo = DeefyRepository::getInstance();
                $user = $repo->findUserByEmail($email);

                if ($user && password_verify($pass, $user['passwd'])) {
                    $_SESSION['user'] = [
                        'id' => (int)$user['id'],
                        'email' => $user['email'],
                        'role' => (int)$user['role']
                    ];
                    return "<p class='success'>Connexion réussie ! <a href='?action=my-playlists'>Voir mes playlists</a></p>";
                }
            }
            return "<p class='error'>Identifiants incorrects.</p>";
        }

        return "<h2>Authentification</h2>
        <form method='POST' action='?action=signin'>
            <label>Email : <input type='email' name='email' required></label>
            <label>Mot de passe : <input type='password' name='password' required></label>
            <button type='submit'>Se connecter</button>
        </form>";
    }
}