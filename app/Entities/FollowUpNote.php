<?php

namespace App\Entities;

class FollowUpNote
{
  private ?int $id = null;
  private int $folderId;
  private ?string $text;
  private string $createdAt;
  private ?string $updatedAt = null;

  public function __construct(int $folderId, string $text)
  {
    $this->folderId = $folderId;
    $this->text = $text;

    $now = date('c');
    $this->createdAt = $now;
  }

  public static function fromArray(array $data): self
  {
    if (!isset($data['folder_id']) || !isset($data['text'])) {
      throw new \InvalidArgumentException('folder_id et text sont obligatoires pour créer un followUpNote');
    }

    $examination = new self(
      (int)$data['folder_id'],
      (string)$data['text'],
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
      'text'         => $this->text,
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

  public function getText(): string
  {
    return $this->text;
  }
  public function setText(string $text): void
  {
    $this->text = $text;
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
