<?php

namespace App\Repositories;

use App\Entities\PdfParameter;
use PDO;

class PdfParameterRepository
{
  public function __construct(private PDO $pdo)
  {
    $this->initSchema();
  }

  private function initSchema(): void
  {
    $this->pdo->exec("
        CREATE TABLE IF NOT EXISTS pdf_parameter (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            prescriber_id INTEGER NULL,
            type TEXT NOT NULL DEFAULT 'custom',
            office TEXT NULL,
            subject TEXT NULL DEFAULT 'Compte rendu',
            notes TEXT NULL,
            showTabA INTEGER NOT NULL DEFAULT 1,
            showTabB INTEGER NOT NULL DEFAULT 1,
            showTabC INTEGER NOT NULL DEFAULT 1,
            showTabD INTEGER NOT NULL DEFAULT 1
        )
    ");

    $stmt = $this->pdo->query("SELECT COUNT(*) AS c FROM pdf_parameter WHERE type = 'global'");
    $count = (int)$stmt->fetch(PDO::FETCH_ASSOC)['c'];

    if ($count === 0) {
      $this->pdo->exec("
          INSERT INTO pdf_parameter (
              office, prescriber_id, subject, notes,
              showTabA, showTabB, showTabC, showTabD,
              type
          ) VALUES (
              '', null , 'Compte rendu', '',
              1, 1, 1, 1,
              'global'
          )
      ");
    }
  }

  public function find(int $id): ?PdfParameter
  {
    $stmt = $this->pdo->prepare("SELECT * FROM pdf_parameter WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row ? PdfParameter::fromArray($row) : null;
  }

  public function findByFolder(int $id): ?PdfParameter
  {
    $stmt = $this->pdo->prepare("
        SELECT pp.*
        FROM folder f
        JOIN pdf_parameter pp ON pp.id = f.pdf_parameter_id
        WHERE f.id = :folder_id;
    ");
    $stmt->execute([':folder_id' => $id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row ? PdfParameter::fromArray($row) : null;
  }

  public function findGlobal(): ?PdfParameter
  {
    $stmt = $this->pdo->query("
        SELECT * FROM pdf_parameter
        WHERE type = 'global'
        ORDER BY id ASC
        LIMIT 1
    ");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row ? PdfParameter::fromArray($row) : null;
  }

  public function create(PdfParameter $pdfParameter): PdfParameter
  {
    if ($pdfParameter->getId() !== null) {
      throw new \RuntimeException('PdfParameterRepository::save() ne doit être utilisé que pour créer (id null). Utilise update().');
    }

    $pdfParameter->setType('custom');

    $stmt = $this->pdo->prepare("
        INSERT INTO pdf_parameter (
            office, prescriber_id, subject, notes,
            showTabA, showTabB, showTabC, showTabD,
            type
        ) VALUES (
            :office, :prescriber_id, :subject, :notes,
            :a, :b, :c, :d,
            :type
        )
    ");

    $stmt->execute([
      ':office'        => $pdfParameter->getOffice(),
      ':prescriber_id' => $pdfParameter->getPrescriberId(),
      ':subject'       => $pdfParameter->getSubject(),
      ':notes'         => $pdfParameter->getNotes(),
      ':a'             => $pdfParameter->getShowTabA() ? 1 : 0,
      ':b'             => $pdfParameter->getShowTabB() ? 1 : 0,
      ':c'             => $pdfParameter->getShowTabC() ? 1 : 0,
      ':d'             => $pdfParameter->getShowTabD() ? 1 : 0,
      ':type'          => $pdfParameter->getType(),
    ]);

    $pdfParameter->setId((int)$this->pdo->lastInsertId());

    return $pdfParameter;
  }

  public function update(PdfParameter $pdfParameter): PdfParameter
  {
    if ($pdfParameter->getId() === null) {
      throw new \RuntimeException('PdfParameterRepository::update() nécessite un id. Utilise save().');
    }

    $stmt = $this->pdo->prepare("
        UPDATE pdf_parameter SET
            office        = :office,
            prescriber_id = :prescriber_id,
            subject       = :subject,
            notes         = :notes,
            showTabA    = :a,
            showTabB    = :b,
            showTabC    = :c,
            showTabD    = :d
        WHERE id = :id
    ");

    $stmt->execute([
      ':office'        => $pdfParameter->getOffice(),
      ':prescriber_id' => $pdfParameter->getPrescriberId(),
      ':subject'       => $pdfParameter->getSubject(),
      ':notes'         => $pdfParameter->getNotes(),
      ':a'             => $pdfParameter->getShowTabA() ? 1 : 0,
      ':b'             => $pdfParameter->getShowTabB() ? 1 : 0,
      ':c'             => $pdfParameter->getShowTabC() ? 1 : 0,
      ':d'             => $pdfParameter->getShowTabD() ? 1 : 0,
      ':id'            => $pdfParameter->getId(),
    ]);

    return $pdfParameter;
  }
}
