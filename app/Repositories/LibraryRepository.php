<?php

namespace App\Repositories;

use App\Entities\Library;
use PDO;

class LibraryRepository
{
  public function __construct(private PDO $pdo)
  {
    $this->initSchema();
  }

  private function initSchema(): void
  {
    $this->pdo->exec("
        CREATE TABLE IF NOT EXISTS library (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            value TEXT NOT NULL
        )
    ");

    $stmt = $this->pdo->query("SELECT COUNT(*) AS c FROM library");
    $count = (int)($stmt->fetch(PDO::FETCH_ASSOC)['c'] ?? 0);

    if ($count === 0) {
      $this->pdo->exec("
        INSERT INTO library (name, value) VALUES
        ('localisation', 'cheville'),
        ('localisation', 'droite'),
        ('localisation', 'face dorsale'),
        ('localisation', 'face plantaire'),
        ('localisation', 'gauche'),
        ('localisation', 'genou'),
        ('localisation', 'hallux'),
        ('localisation', 'latéral'),
        ('localisation', 'médial'),
        ('localisation', 'orteils'),
        ('localisation', 'pied'),
        ('localisation', 'talon'),

        ('observation', 'affaissement du naviculaire'),
        ('observation', 'avant-pied rond'),
        ('observation', 'cal'),
        ('observation', 'cor'),
        ('observation', 'genu varum'),
        ('observation', 'genu valgum'),
        ('observation', 'hallux valgus'),
        ('observation', 'hyper kératose'),
        ('observation', 'pied carré'),
        ('observation', 'pied creux'),
        ('observation', 'pied egyptien'),
        ('observation', 'pied grec'),
        ('observation', 'pied plat'),
        ('observation', 'point d''appui'),
        ('observation', 'valgus calcanéen'),
        ('observation', 'varus calcanéen'),

        ('equipmentList', 'orthèses plantaires'),
        ('equipmentList', 'orthèse thermoformé de maintien de la colonne du pouce'),
        ('equipmentList', 'orthèse thermoformé dorso palmaire'),
        ('equipmentList', 'orthèse thermoformé MP stop'),
        ('equipmentList', 'orthèse thermoformé poignet'),
        ('equipmentList', 'orthèse thermoformé poignet main doigt'),
        ('equipmentList', 'orthèse thermoformé poignet pouce'),
        ('equipmentList', 'orthèse thermoformé thomine'),

        ('equipmentDetail', 'anneau talonnier'),
        ('equipmentDetail', 'anneau talonnier supinateur'),
        ('equipmentDetail', 'anneau talonnier pronateur'),
        ('equipmentDetail', 'appui sous diaphysaire'),
        ('equipmentDetail', 'ARCM - Appui rétro capital médian'),
        ('equipmentDetail', 'BAC de décharge'),
        ('equipmentDetail', 'bande de varus'),
        ('equipmentDetail', 'BRC - Barre rétro capital'),
        ('equipmentDetail', 'ESP - Element supinateur postérieur'),
        ('equipmentDetail', 'EPP - Element pronateur postérieur'),
        ('equipmentDetail', 'sac'),
        ('equipmentDetail', 'sous cuboïdien'),
        ('equipmentDetail', 'sous naviculaire'),
        ('equipmentDetail', 'voûte concave'),
        ('equipmentDetail', 'voûte neutre');
      ");
    }
  }

  public function list(): array
  {
    $stmt = $this->pdo->query("SELECT * FROM library");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $rows ? array_map(fn($r) => Library::fromArray($r), $rows) : [];
  }

  public function find(int $id): ?Library
  {
    $stmt = $this->pdo->prepare("SELECT * FROM library WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row ? Library::fromArray($row) : null;
  }

  public function create(Library $library): Library
  {
    if ($library->getId() !== null) {
      throw new \RuntimeException('LibraryRepository::create() ne doit être utilisé que pour créer (id null). Utilise update().');
    }

    $stmt = $this->pdo->prepare("
        INSERT INTO library (
            name, value
        ) VALUES (
            :name, :value
        )
    ");

    $stmt->execute([
      ':name'  => $library->getName(),
      ':value' => $library->getValue(),
    ]);

    $library->setId((int)$this->pdo->lastInsertId());

    return $library;
  }

  public function delete(int $id): void
  {
    $stmt = $this->pdo->prepare("DELETE FROM library WHERE id = :id");
    $stmt->execute([':id' => $id]);
  }
}
