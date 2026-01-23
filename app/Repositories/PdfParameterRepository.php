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
            type TEXT NOT NULL DEFAULT 'custom',
            office TEXT NULL,
            prescriberFullname TEXT NULL,
            prescriberAddress TEXT NULL,
            prescriberMail TEXT NULL,
            prescriberPhoneNumber TEXT NULL,
            subject TEXT NULL DEFAULT 'Compte rendu du bilan podologique de {genre} {nom_complet}.',
            content TEXT '{genre} {nom_complet}, née le {date_de_naissance}, est venu le {date_creation_dossier} afin de réaliser un bilan podologique au sein de mon cabinet.\n\nRépondant à votre prescription médicale, je me permet de vous retourner le compte rendu complet de cet examen.\n\nFait le : {date_aujourdhui}.',
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
              office, prescriberFullname, prescriberAddress, prescriberMail, prescriberPhoneNumber,
              subject, content, notes, showTabA, showTabB, showTabC, showTabD, type
          ) VALUES (
              '', '' ,'' ,'' ,'' ,
              'Compte rendu du bilan podologique de {genre} {nom_complet}.',
              '{genre} {nom_complet}, née le {date_de_naissance}, est venu le {date_creation_dossier} afin de réaliser un bilan podologique au sein de mon cabinet.\n\nRépondant à votre prescription médicale, je me permet de vous retourner le compte rendu complet de cet examen.\n\nFait le : {date_aujourdhui}.'
              , '', 1, 1, 1, 1, 'global'
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
            office, prescriberFullname, prescriberAddress, prescriberMail, prescriberPhoneNumber,
            subject, content, notes, showTabA, showTabB, showTabC, showTabD, type
        ) VALUES (
            :office, :prescriberFullname, :prescriberAddress, :prescriberMail, :prescriberPhoneNumber,
            :subject, :content, :notes, :showTabA, :showTabB, :showTabC, :showTabD, :type
        )
    ");

    $stmt->execute([
      ':type'                  => $pdfParameter->getType(),
      ':office'                => $pdfParameter->getOffice(),
      ':prescriberFullname'    => $pdfParameter->getPrescriberFullname(),
      ':prescriberAddress'     => $pdfParameter->getPrescriberAddress(),
      ':prescriberMail'        => $pdfParameter->getPrescriberMail(),
      ':prescriberPhoneNumber' => $pdfParameter->getPrescriberPhoneNumber(),
      ':subject'               => $pdfParameter->getSubject(),
      ':content'               => $pdfParameter->getContent(),
      ':notes'                 => $pdfParameter->getNotes(),
      ':showTabA'              => $pdfParameter->getShowTabA() ? 1 : 0,
      ':showTabB'              => $pdfParameter->getShowTabB() ? 1 : 0,
      ':showTabC'              => $pdfParameter->getShowTabC() ? 1 : 0,
      ':showTabD'              => $pdfParameter->getShowTabD() ? 1 : 0,
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
            office                = :office,
            prescriberFullname    = :prescriberFullname,
            prescriberAddress     = :prescriberAddress,
            prescriberMail        = :prescriberMail,
            prescriberPhoneNumber = :prescriberPhoneNumber,
            subject               = :subject,
            content               = :content,
            notes                 = :notes,
            showTabA              = :showTabA,
            showTabB              = :showTabB,
            showTabC              = :showTabC,
            showTabD              = :showTabD
        WHERE id = :id
    ");

    $stmt->execute([
      ':office'                => $pdfParameter->getOffice(),
      ':prescriberFullname'    => $pdfParameter->getPrescriberFullname(),
      ':prescriberAddress'     => $pdfParameter->getPrescriberAddress(),
      ':prescriberMail'        => $pdfParameter->getPrescriberMail(),
      ':prescriberPhoneNumber' => $pdfParameter->getPrescriberPhoneNumber(),
      ':subject'               => $pdfParameter->getSubject(),
      ':content'               => $pdfParameter->getContent(),
      ':notes'                 => $pdfParameter->getNotes(),
      ':showTabA'              => $pdfParameter->getShowTabA() ? 1 : 0,
      ':showTabB'              => $pdfParameter->getShowTabB() ? 1 : 0,
      ':showTabC'              => $pdfParameter->getShowTabC() ? 1 : 0,
      ':showTabD'              => $pdfParameter->getShowTabD() ? 1 : 0,
      ':id'                    => $pdfParameter->getId(),
    ]);

    return $pdfParameter;
  }
}
