<?php

namespace App\Entities;

class Examination
{
  private ?int $id = null;
  private int $folderId;
  private string $name;
  private string $localisation = '';
  private string $observation = '';
  private string $createdAt;
  private ?string $updatedAt = null;

  public function __construct(int $folderId, string $name, string $localisation, string $observation)
  {
    $this->folderId = $folderId;
    $this->name = $name;
    $this->localisation = $localisation;
    $this->observation = $observation;

    $now = date('c');
    $this->createdAt = $now;
  }

  public static function fromArray(array $data): self
  {
    if (!isset($data['folder_id']) || !isset($data['name']) || !isset($data['localisation']) || !isset($data['observation'])) {
      throw new \InvalidArgumentException('folder_id, name, localisation et observation sont obligatoires pour créer un examination');
    }

    $examination = new self(
      (int)$data['folder_id'],
      (string)$data['name'],
      (string)$data['localisation'],
      (string)$data['observation']
    );

    if (isset($data['id'])) $examination->setId((int)$data['id']);
    if (!empty($data['createdAt'])) $examination->setCreatedAt($data['createdAt']);
    if (!empty($data['updatedAt'])) $examination->setUpdatedAt($data['updatedAt']);

    return $examination;
  }

  public function toArray(): array
  {
    return [
      'id'           => $this->id,
      'folder_id'    => $this->folderId,
      'name'         => $this->name,
      'localisation' => $this->localisation,
      'observation'  => $this->observation,
      'createdAt'    => $this->createdAt,
      'updatedAt'    => $this->updatedAt,
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

  public function getfolderId(): int
  {
    return $this->folderId;
  }
  public function setfolderId(int $folderId): void
  {
    $this->folderId = $folderId;
  }

  public function getName(): string
  {
    return $this->name;
  }
  public function setName(string $name): void
  {
    $this->name = $name;
  }

  public function getLocalisation(): string
  {
    return $this->localisation;
  }
  public function setLocalisation(string $localisation): void
  {
    $this->localisation = $localisation;
  }

  public function getObservation(): string
  {
    return $this->observation;
  }
  public function setObservation(string $observation): void
  {
    $this->observation = $observation;
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
}
