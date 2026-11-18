<?php
declare(strict_types=1);

namespace deefy\classes\Action;

class LogoutAction extends Action {
    public function execute(): string {
        session_destroy();
        $_SESSION = [];
        return "<p class='success'>Vous avez été déconnecté.</p>";
    }
}