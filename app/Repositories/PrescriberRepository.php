<?php

namespace App\Repositories;

use App\Entities\Prescriber;
use PDO;

class PrescriberRepository
{
  public function __construct(private PDO $pdo)
  {
    $this->initSchema();
  }

  private function initSchema(): void
  {
    $this->pdo->exec("
        CREATE TABLE IF NOT EXISTS prescriber (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            fullname TEXT NOT NULL,
            address TEXT NULL,
            mail TEXT NULL,
            phoneNumber TEXT NULL
        )
    ");

    $this->pdo->exec("CREATE INDEX IF NOT EXISTS idx_prescriber_fullname ON prescriber(fullname)");
  }

  public function list(): array
  {
    $stmt = $this->pdo->query("SELECT * FROM prescriber ORDER BY fullname");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $rows ? array_map(fn($r) => Prescriber::fromArray($r), $rows) : [];
  }

  public function find(int $id): ?Prescriber
  {
    $stmt = $this->pdo->prepare("SELECT * FROM prescriber WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row ? Prescriber::fromArray($row) : null;
  }

  public function create(Prescriber $prescriber): Prescriber
  {
    if ($prescriber->getId() !== null) {
      throw new \RuntimeException('PrescriberRepository::save() ne doit être utilisé que pour créer un prescriber sans id. Utilise update().');
    }

    $stmt = $this->pdo->prepare("
        INSERT INTO prescriber (
            fullname, address, mail, phoneNumber
        ) VALUES (
            :fullname, :address, :mail, :phoneNumber
        )
    ");

    $stmt->execute([
      ':fullname'    => $prescriber->getFullname(),
      ':address'     => $prescriber->getAddress(),
      ':mail'        => $prescriber->getMail(),
      ':phoneNumber' => $prescriber->getPhoneNumber()
    ]);

    $prescriber->setId((int)$this->pdo->lastInsertId());

    return $prescriber;
  }

  public function update(Prescriber $prescriber): Prescriber
  {
    if ($prescriber->getId() === null) {
      throw new \RuntimeException('PrescriberRepository::update() nécessite un prescriber avec id. Utilise save() pour créer.');
    }

    $stmt = $this->pdo->prepare("
        UPDATE prescriber SET
            fullname    = :fullname,
            address     = :address,
            mail        = :mail,
            phoneNumber = :phoneNumber
        WHERE id = :id
    ");

    $stmt->execute([
      ':fullname'    => $prescriber->getFullname(),
      ':address'     => $prescriber->getAddress(),
      ':mail'        => $prescriber->getMail(),
      ':phoneNumber' => $prescriber->getPhoneNumber(),
      ':id'          => $prescriber->getId()
    ]);

    return $prescriber;
  }

  public function delete(int $id): void
  {
    $stmt = $this->pdo->prepare("DELETE FROM prescriber WHERE id = :id");
    $stmt->execute([':id' => $id]);
  }
}
