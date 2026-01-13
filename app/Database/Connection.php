<?php

namespace App\Database;

use PDO;
use PDOException;

class Connection
{
  private static ?PDO $instance = null;

  public static function getInstance(string $dbPath): PDO
  {
    // Si aucune instance → on crée la connexion
    if (self::$instance === null) {

      try {
        // Création du dossier data/ si nécessaire
        $dir = dirname($dbPath);
        if (!is_dir($dir)) {
          mkdir($dir, 0777, true);
        }

        self::$instance = new PDO('sqlite:' . $dbPath);
        self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch (PDOException $e) {
        die('Erreur de connexion SQLite : ' . $e->getMessage());
      }
    }

    // Retourne toujours la même instance
    return self::$instance;
  }
}
