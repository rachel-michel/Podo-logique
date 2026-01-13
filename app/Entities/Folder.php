<?php

namespace App\Entities;

class Folder
{
  private ?int $id = null;
  private int $patientId;
  private int $pdfParameterId;
  private string $name;
  private string $createdAt;
  private ?string $updatedAt = null;
  private ?string $archivedAt = null;

  public function __construct(int $patientId, int $pdfParameterId, string $name)
  {
    $this->patientId = $patientId;
    $this->pdfParameterId = $pdfParameterId;
    $this->name = $name;

    $now = date('c');
    $this->createdAt = $now;
  }

  public static function fromArray(array $data): self
  {
    if (!isset($data['patient_id']) || !isset($data['name']) || !isset($data['pdf_parameter_id'])) {
      throw new \InvalidArgumentException('patient_id, pdf_parameter_id et name sont obligatoires pour créer un Folder');
    }

    $folder = new self(
      (int)$data['patient_id'],
      (int)$data['pdf_parameter_id'],
      (string)$data['name']
    );

    if (isset($data['id'])) $folder->setId((int)$data['id']);
    if (!empty($data['createdAt'])) $folder->setCreatedAt($data['createdAt']);
    if (!empty($data['updatedAt'])) $folder->setUpdatedAt($data['updatedAt']);
    if (!empty($data['archivedAt'])) $folder->setArchivedAt($data['archivedAt']);

    return $folder;
  }

  public function toArray(): array
  {
    return [
      'id'         =>       $this->id,
      'patient_id' =>       $this->patientId,
      'pdf_parameter_id' => $this->pdfParameterId,
      'name'       =>       $this->name,
      'createdAt'  =>       $this->createdAt,
      'updatedAt'  =>       $this->updatedAt,
      'archivedAt' =>       $this->archivedAt,
    ];
  }

  public function getId(): ?int
  {
    return $this->id;
  }
  public function setId(int $id): void
  {
    $this->id = $id;
  }

  public function getPatientId(): int
  {
    return $this->patientId;
  }
  public function setPatientId(int $patientId): void
  {
    $this->patientId = $patientId;
  }

  public function getPdfParameterId(): int
  {
    return $this->pdfParameterId;
  }
  public function setPdfParameterId(int $id): void
  {
    $this->pdfParameterId = $id;
  }

  public function getName(): string
  {
    return $this->name;
  }
  public function setName(string $name): void
  {
    $this->name = $name;
  }

  public function getCreatedAt(): string
  {
    return $this->createdAt;
  }
  public function setCreatedAt(string $date): void
  {
    $this->createdAt = $date;
  }

  public function getUpdatedAt(): ?string
  {
    return $this->updatedAt;
  }
  public function setUpdatedAt(string $date): void
  {
    $this->updatedAt = $date;
  }

  public function getArchivedAt(): ?string
  {
    return $this->archivedAt;
  }
  public function setArchivedAt(string $date): void
  {
    $this->archivedAt = $date;
  }
}
