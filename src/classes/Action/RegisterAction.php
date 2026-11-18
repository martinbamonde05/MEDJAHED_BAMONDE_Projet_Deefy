<?php
declare(strict_types=1);

namespace deefy\classes\Action;

use deefy\classes\Repository\DeefyRepository;

class RegisterAction extends Action {
    public function execute(): string {
        if ($this->http_method === 'POST') {
            $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
            $pass = $_POST['password'] ?? '';

            if (!$email || strlen($pass) < 6) {
                return "<p class='error'>Email invalide ou mot de passe trop court (6 caractères minimum).</p>";
            }

            $repo = DeefyRepository::getInstance();
            if ($repo->registerUser($email, $pass)) {
                return "<p class='success'>Inscription réussie ! <a href='?action=signin'>Connectez-vous ici</a>.</p>";
            }
            return "<p class='error'>Cet email est déjà utilisé.</p>";
        }

        return "<h2>Inscription</h2>
        <form method='POST' action='?action=register'>
            <label>Email : <input type='email' name='email' required></label>
            <label>Mot de passe (6 car. min) : <input type='password' name='password' required></label>
            <button type='submit'>S'inscrire</button>
        </form>";
    }
}