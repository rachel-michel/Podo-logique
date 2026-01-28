<?php

namespace App\Repositories;

use App\Entities\FollowUpNote;
use PDO;

class FollowUpNoteRepository
{
  public function __construct(private PDO $pdo)
  {
    $this->initSchema();
  }

  private function initSchema(): void
  {
    $this->pdo->exec('PRAGMA foreign_keys = ON');

    $this->pdo->exec("
        CREATE TABLE IF NOT EXISTS follow_up_note (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            folder_id INTEGER NOT NULL,
            text TEXT NULL,
            createdAt TEXT NOT NULL,
            updatedAt TEXT NULL,
            CONSTRAINT fk_follow_up_note_folder
                FOREIGN KEY (folder_id)
                REFERENCES folder(id)
        )
    ");

    $this->pdo->exec("CREATE INDEX IF NOT EXISTS idx_follow_up_note_folder ON follow_up_note(folder_id)");
  }

  public function find(int $id): ?FollowUpNote
  {
    $stmt = $this->pdo->prepare("SELECT * FROM follow_up_note WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row ? FollowUpNote::fromArray($row) : null;
  }

  public function findByFolder(int $folderId): array
  {
    $stmt = $this->pdo->prepare("SELECT * FROM follow_up_note WHERE folder_id = :pid ORDER BY id DESC");
    $stmt->execute([':pid' => $folderId]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $rows ? array_map(fn($r) => FollowUpNote::fromArray($r), $rows) : [];
  }

  public function create(FollowUpNote $followUpNote): FollowUpNote
  {
    if ($followUpNote->getId() !== null) {
      throw new \RuntimeException('FollowUpNote::save() ne doit être utilisé que pour créer un followUpNote sans id. Utilise update().');
    }

    $now = date('c');
    $followUpNote->setCreatedAt($now);

    $stmt = $this->pdo->prepare("
        INSERT INTO follow_up_note (
            folder_id, text, createdAt, updatedAt
        ) VALUES (
            :folder_id, :text, :createdAt, :updatedAt
        )
    ");

    $stmt->execute([
      ':folder_id'        => $followUpNote->getFolderId(),
      ':text'             => $followUpNote->getText(),
      ':createdAt'        => $followUpNote->getCreatedAt(),
      ':updatedAt'        => $followUpNote->getUpdatedAt(),
    ]);

    $followUpNote->setId((int)$this->pdo->lastInsertId());

    return $followUpNote;
  }

  public function update(FollowUpNote $followUpNote): FollowUpNote
  {
    if ($followUpNote->getId() === null) {
      throw new \RuntimeException('FollowUpNoteRepository::update() nécessite un followUpNote avec id. Utilise save() pour créer.');
    }

    $followUpNote->setUpdatedAt(date('c'));

    $stmt = $this->pdo->prepare("
        UPDATE follow_up_note SET
            folder_id        = :folder_id,
            text             = :text,
            createdAt        = :createdAt,
            updatedAt        = :updatedAt
        WHERE id = :id
    ");

    $stmt->execute([
      ':folder_id'        => $followUpNote->getFolderId(),
      ':text'             => $followUpNote->getText(),
      ':createdAt'        => $followUpNote->getCreatedAt(),
      ':updatedAt'        => $followUpNote->getUpdatedAt(),
      ':id'              =>  $followUpNote->getId(),
    ]);

    return $followUpNote;
  }

  public function delete(int $id): void
  {
    $stmt = $this->pdo->prepare("DELETE FROM follow_up_note WHERE id = :id");
    $stmt->execute([':id' => $id]);
  }
}
