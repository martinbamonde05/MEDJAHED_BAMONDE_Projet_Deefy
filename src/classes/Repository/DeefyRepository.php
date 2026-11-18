<?php
declare(strict_types=1);

namespace deefy\classes\Repository;

use deefy\classes\Playlist;
use deefy\classes\AlbumTrack;
use \PDO;
use \Exception;

class DeefyRepository {
    private PDO $pdo;
    private static ?DeefyRepository $instance = null;
    private static array $config = [];

    private function __construct(array $conf) {
        $this->pdo = new PDO($conf['dsn'], $conf['user'], $conf['pass'], [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_STRINGIFY_FETCHES => false
        ]);
        $this->pdo->prepare("SET NAMES 'UTF8'")->execute();
    }

    public static function setConfig(string $file): void {
        $conf = parse_ini_file($file);
        if ($conf === false) {
            throw new Exception("Erreur de lecture du fichier de configuration.");
        }
        self::$config = [
            'dsn' => "{$conf['driver']}:host={$conf['host']};dbname={$conf['database']}",
            'user' => $conf['username'],
            'pass' => $conf['password']
        ];
    }

    public static function getInstance(): self {
        if (is_null(self::$instance)) {
            self::$instance = new DeefyRepository(self::$config);
        }
        return self::$instance;
    }
	
    public function findUserByEmail(string $email): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM user WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    public function registerUser(string $email, string $password): bool {
        if ($this->findUserByEmail($email) !== null) {
            return false; // Email déjà utilisé
        }
        $hash = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
        $stmt = $this->pdo->prepare("INSERT INTO user (email, passwd, role) VALUES (:email, :passwd, 1)");
        return $stmt->execute(['email' => $email, 'passwd' => $hash]);
    }

    public function findPlaylistsByUser(int $userId): array {
        $query = "SELECT p.id, p.nom 
                  FROM playlist p 
                  INNER JOIN user2playlist u2p ON p.id = u2p.id_pl 
                  WHERE u2p.id_user = :uid";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['uid' => $userId]);

        $playlists = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $pl = new Playlist($row['nom']);
            $pl->setID((int)$row['id']);
            $playlists[] = $pl;
        }
        return $playlists;
    }

    public function isPlaylistOwner(int $userId, int $playlistId): bool {
        $stmt = $this->pdo->prepare("SELECT 1 FROM user2playlist WHERE id_user = :uid AND id_pl = :plid");
        $stmt->execute(['uid' => $userId, 'plid' => $playlistId]);
        return $stmt->fetch() !== false;
    }

    public function findPlaylistById(int $playlistId): ?Playlist {
        $stmt = $this->pdo->prepare("SELECT id, nom FROM playlist WHERE id = :id");
        $stmt->execute(['id' => $playlistId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        $playlist = new Playlist($row['nom']);
        $playlist->setID((int)$row['id']);
        $qTracks = "SELECT t.* FROM track t 
                    INNER JOIN playlist2track p2t ON t.id = p2t.id_track 
                    WHERE p2t.id_pl = :plid 
                    ORDER BY p2t.no_piste_dans_liste ASC";
        $stmtT = $this->pdo->prepare($qTracks);
        $stmtT->execute(['plid' => $playlistId]);

        while ($t = $stmtT->fetch(PDO::FETCH_ASSOC)) {
            $track = new AlbumTrack(
                $t['titre'],
                $t['filename'] ?? '',
                $t['titre_album'] ?? '',
                (int)($t['numero_album'] ?? 1)
            );
            $track->genre = $t['genre'] ?? 'Inconnu';
            $track->duree = (int)$t['duree'];
            $playlist->addTrack($track);
        }

        return $playlist;
    }

    public function saveEmptyPlaylist(Playlist $pl, int $userId): Playlist {
        $stmt = $this->pdo->prepare("INSERT INTO playlist (nom) VALUES (:nom)");
        $stmt->execute(['nom' => $pl->nom]);
        $plId = (int)$this->pdo->lastInsertId();
        $pl->setID($plId);

        $stmtLink = $this->pdo->prepare("INSERT INTO user2playlist (id_user, id_pl) VALUES (:uid, :plid)");
        $stmtLink->execute(['uid' => $userId, 'plid' => $plId]);

        return $pl;
    }


    public function addTrackToPlaylist(int $playlistId, string $titre, string $genre, int $duree, string $artiste, string $album): void {
        $qTrack = "INSERT INTO track (titre, genre, duree, artiste_album, titre_album) 
                   VALUES (:titre, :genre, :duree, :artiste, :album)";
        $stmt = $this->pdo->prepare($qTrack);
        $stmt->execute([
            'titre' => $titre,
            'genre' => $genre,
            'duree' => $duree,
            'artiste' => $artiste,
            'album' => $album
        ]);
        $trackId = (int)$this->pdo->lastInsertId();
        $stmtNum = $this->pdo->prepare("SELECT COALESCE(MAX(no_piste_dans_liste), 0) + 1 FROM playlist2track WHERE id_pl = :plid");
        $stmtNum->execute(['plid' => $playlistId]);
        $nextNum = (int)$stmtNum->fetchColumn();
        $stmtLink = $this->pdo->prepare("INSERT INTO playlist2track (id_pl, id_track, no_piste_dans_liste) VALUES (:plid, :tid, :num)");
        $stmtLink->execute(['plid' => $playlistId, 'tid' => $trackId, 'num' => $nextNum]);
    }
}