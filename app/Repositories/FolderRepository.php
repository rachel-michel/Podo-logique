<?php

namespace App\Repositories;

use App\Entities\Folder;
use PDO;

class FolderRepository
{
  public function __construct(private PDO $pdo)
  {
    $this->initSchema();
  }

  private function initSchema(): void
  {
    $this->pdo->exec('PRAGMA foreign_keys = ON');

    $this->pdo->exec("
        CREATE TABLE IF NOT EXISTS folder (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            patient_id INTEGER NOT NULL,
            pdf_parameter_id INTEGER NOT NULL,
            name TEXT NOT NULL,
            createdAt TEXT NOT NULL,
            updatedAt TEXT NULL,
            archivedAt TEXT NULL,
            CONSTRAINT fk_folder_patient
                FOREIGN KEY (patient_id)
                REFERENCES patient(id),
            CONSTRAINT fk_folder_pdf_parameter
                FOREIGN KEY (pdf_parameter_id)
                REFERENCES pdf_parameter(id)
        )
    ");

    $this->pdo->exec("CREATE INDEX IF NOT EXISTS idx_folder_patient ON folder(patient_id)");
    $this->pdo->exec("CREATE INDEX IF NOT EXISTS idx_folder_pdf_parameter ON folder(pdf_parameter_id)");
  }

  public function find(int $id): ?Folder
  {
    $stmt = $this->pdo->prepare("SELECT * FROM folder WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row ? Folder::fromArray($row) : null;
  }

  public function findByPatient(int $patientId): array
  {
    $stmt = $this->pdo->prepare("SELECT * FROM folder WHERE patient_id = :pid ORDER BY id DESC");
    $stmt->execute([':pid' => $patientId]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $rows ? array_map(fn($r) => Folder::fromArray($r), $rows) : [];
  }

  public function create(Folder $folder): Folder
  {
    if ($folder->getId() !== null) {
      throw new \RuntimeException('FolderRepository::save() ne doit être utilisé que pour créer un folder sans id. Utilise update().');
    }

    $now = date('c');
    $folder->setCreatedAt($now);

    $stmt = $this->pdo->prepare("
        INSERT INTO folder (
            patient_id, name, pdf_parameter_id,
            createdAt, updatedAt, archivedAt
        ) VALUES (
            :patient_id, :name, :pdf_parameter_id,
            :createdAt, :updatedAt, :archivedAt
        )
    ");

    $stmt->execute([
      ':patient_id'      =>  $folder->getPatientId(),
      ':name'            =>  $folder->getName(),
      ':pdf_parameter_id' => $folder->getPdfParameterId(),
      ':createdAt'       =>  $folder->getCreatedAt(),
      ':updatedAt'       =>  $folder->getUpdatedAt(),
      ':archivedAt'      =>  $folder->getArchivedAt(),
    ]);

    $folder->setId((int)$this->pdo->lastInsertId());

    return $folder;
  }

  public function update(Folder $folder): Folder
  {
    if ($folder->getId() === null) {
      throw new \RuntimeException('FolderRepository::update() nécessite un folder avec id. Utilise save() pour créer.');
    }

    $folder->setUpdatedAt(date('c'));

    $stmt = $this->pdo->prepare("
        UPDATE folder SET
            patient_id       = :patient_id,
            name             = :name,
            pdf_parameter_id = :pdf_parameter_id,
            createdAt        = :createdAt,
            updatedAt        = :updatedAt,
            archivedAt       = :archivedAt
        WHERE id = :id
    ");

    $stmt->execute([
      ':patient_id'      =>  $folder->getPatientId(),
      ':name'            =>  $folder->getName(),
      ':pdf_parameter_id' => $folder->getPdfParameterId(),
      ':createdAt'       =>  $folder->getCreatedAt(),
      ':updatedAt'       =>  $folder->getUpdatedAt(),
      ':archivedAt'      =>  $folder->getArchivedAt(),
      ':id'              =>  $folder->getId(),
    ]);

    return $folder;
  }

  public function delete(int $id): void
  {
    $stmt = $this->pdo->prepare("DELETE FROM folder WHERE id = :id");
    $stmt->execute([':id' => $id]);
  }
}
