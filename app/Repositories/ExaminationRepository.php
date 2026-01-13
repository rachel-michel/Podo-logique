<?php

namespace App\Repositories;

use App\Entities\Examination;
use PDO;

class ExaminationRepository
{
  public function __construct(private PDO $pdo)
  {
    $this->initSchema();
  }

  private function initSchema(): void
  {
    $this->pdo->exec('PRAGMA foreign_keys = ON');

    $this->pdo->exec("
        CREATE TABLE IF NOT EXISTS examination (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            folder_id INTEGER NOT NULL,
            name TEXT NOT NULL,
            localisation TEXT NOT NULL,
            observation TEXT NOT NULL,
            createdAt TEXT NOT NULL,
            updatedAt TEXT NULL,
            CONSTRAINT fk_examination_patient
                FOREIGN KEY (folder_id)
                REFERENCES folder(id)
        )
    ");

    $this->pdo->exec("CREATE INDEX IF NOT EXISTS idx_examination_folder ON examination(folder_id)");
  }

  public function find(int $id): ?Examination
  {
    $stmt = $this->pdo->prepare("SELECT * FROM examination WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row ? Examination::fromArray($row) : null;
  }

  public function findByFolder(int $folderId): array
  {
    $stmt = $this->pdo->prepare("SELECT * FROM examination WHERE folder_id = :pid ORDER BY id DESC");
    $stmt->execute([':pid' => $folderId]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $rows ? array_map(fn($r) => Examination::fromArray($r), $rows) : [];
  }

  public function create(Examination $examination): Examination
  {
    if ($examination->getId() !== null) {
      throw new \RuntimeException('ExaminationRepository::save() ne doit être utilisé que pour créer un examination sans id. Utilise update().');
    }

    $now = date('c');
    $examination->setCreatedAt($now);

    $stmt = $this->pdo->prepare("
        INSERT INTO examination (
            folder_id, name, localisation, observation,
            createdAt, updatedAt
        ) VALUES (
            :folder_id, :name, :localisation, :observation,
            :createdAt, :updatedAt
        )
    ");

    $stmt->execute([
      ':folder_id'        => $examination->getFolderId(),
      ':name'             => $examination->getName(),
      ':localisation'     => $examination->getLocalisation(),
      ':observation'     => $examination->getObservation(),
      ':createdAt'        => $examination->getCreatedAt(),
      ':updatedAt'        => $examination->getUpdatedAt(),
    ]);

    $examination->setId((int)$this->pdo->lastInsertId());

    return $examination;
  }

  public function update(Examination $examination): Examination
  {
    if ($examination->getId() === null) {
      throw new \RuntimeException('ExaminationRepository::update() nécessite un examination avec id. Utilise save() pour créer.');
    }

    $examination->setUpdatedAt(date('c'));

    $stmt = $this->pdo->prepare("
        UPDATE examination SET
            folder_id        = :folder_id,
            name             = :name,
            localisation     = :localisation,
            observation     = :observation,
            createdAt        = :createdAt,
            updatedAt        = :updatedAt
        WHERE id = :id
    ");

    $stmt->execute([
      ':folder_id'        => $examination->getFolderId(),
      ':name'             => $examination->getName(),
      ':localisation'     => $examination->getLocalisation(),
      ':observation'     => $examination->getObservation(),
      ':createdAt'        => $examination->getCreatedAt(),
      ':updatedAt'        => $examination->getUpdatedAt(),
      ':id'              =>  $examination->getId(),
    ]);

    return $examination;
  }

  public function delete(int $id): void
  {
    $stmt = $this->pdo->prepare("DELETE FROM examination WHERE id = :id");
    $stmt->execute([':id' => $id]);
  }
}
