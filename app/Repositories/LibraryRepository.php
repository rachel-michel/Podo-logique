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

        ('equipmentList', 'Orthèses plantaires'),
        ('equipmentList', 'Anneau neutre'),
        ('equipmentList', 'Anneau pronateur'),
        ('equipmentList', 'Anneau supinateur'),
        ('equipmentList', 'Arciforme'),
        ('equipmentList', 'ARCM - appui retro capital médian'),
        ('equipmentList', 'BAC - barre antéro capitale totale'),
        ('equipmentList', 'Décharge de l''hallux'),
        ('equipmentList', 'Décharge des orteils 1-2'),
        ('equipmentList', 'Décharge du 2eme orteil'),
        ('equipmentList', 'Décharge des orteils 2-3'),
        ('equipmentList', 'Décharge des orteils 2-3-4'),
        ('equipmentList', 'Décharge du 3eme orteil'),
        ('equipmentList', 'Décharge des orteils 3-4'),
        ('equipmentList', 'Décharge des orteils 3-4-5'),
        ('equipmentList', 'Décharge du 4eme orteil'),
        ('equipmentList', 'Décharge des orteils 4-5'),
        ('equipmentList', 'Décharge du 5eme orteil'),
        ('equipmentList', 'Barre sous diaphysaire'),
        ('equipmentList', 'BRC - Barre rétro capital'),
        ('equipmentList', 'CPP - coin pronateur postérieur'),
        ('equipmentList', 'CSP - coin supinateur postérieur'),
        ('equipmentList', 'EPA - élément pronateur antérieur'),
        ('equipmentList', 'EPP - élément pronateur postérieur'),
        ('equipmentList', 'EPT - élément pronateur total'),
        ('equipmentList', 'ESP - élément supinateur postérieur'),
        ('equipmentList', 'Hémicoupole de décharge de l''hallux'),
        ('equipmentList', 'SAC - sous antéro capital court'),
        ('equipmentList', 'SAC - sous antéro capital long'),
        ('equipmentList', 'Sous cuboïdien'),
        ('equipmentList', 'Sous naviculaire'),
        ('equipmentList', 'Talonnette'),
        ('equipmentList', 'Voûte'),

        ('equipmentList', 'Orthèse thermoformé de maintien de la colonne du pouce'),
        ('equipmentList', 'Orthèse thermoformé dorso palmaire'),
        ('equipmentList', 'Orthèse thermoformé MP stop'),
        ('equipmentList', 'Orthèse thermoformé poignet'),
        ('equipmentList', 'Orthèse thermoformé poignet main doigt'),
        ('equipmentList', 'Orthèse thermoformé poignet pouce'),
        ('equipmentList', 'Orthèse thermoformé thomine'),

        ('equipmentDetail', 'Liège beige'),
        ('equipmentDetail', 'Liège latex orange'),
        ('equipmentDetail', 'Mousse EVA verte'),
        ('equipmentDetail', 'Mousse EVA grise'),
        ('equipmentDetail', 'Base liège beige'),
        ('equipmentDetail', 'Base liège bleu'),
        ('equipmentDetail', 'Recouvrement sport orange'),
        ('equipmentDetail', 'Recouvrement sport bleu pétrol'),
        ('equipmentDetail', 'Recouvrement diabétique beige'),
        ('equipmentDetail', 'Recouvrement doux léopard')
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
