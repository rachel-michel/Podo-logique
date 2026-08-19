<?php

namespace App\Entities;

class PdfParameter
{
  private ?int $id = null;
  private string $type = 'custom';
  private ?string $office = null;
  private string $subject = 'Compte rendu du bilan podologique de {genre} {nom_complet}.';
  private ?string $content = '{genre} {nom_complet}, née le {date_de_naissance}, est venu le {date_creation_dossier} afin de réaliser un bilan podologique au sein de mon cabinet.\n\nRépondant à votre prescription médicale, je me permet de vous retourner le compte rendu complet de cet examen.\n\nFait le : {date_aujourdhui}.';
  private ?string $notes = null;
  private bool $showTabA = true;
  private bool $showTabB = true;
  private bool $showTabC = true;
  private bool $showTabD = true;

  public static function fromArray(array $data): self
  {
    $e = new self();

    if (isset($data['id'])) $e->setId((int)$data['id']);
    if (isset($data['type'])) $e->setType($data['type']);
    if (isset($data['office'])) $e->setOffice($data['office']);
    if (isset($data['subject'])) $e->setSubject($data['subject']);
    if (isset($data['content'])) $e->setContent($data['content']);
    if (isset($data['notes'])) $e->setNotes($data['notes']);
    if (isset($data['showTabA'])) $e->setShowTabA((bool)$data['showTabA']);
    if (isset($data['showTabB'])) $e->setShowTabB((bool)$data['showTabB']);
    if (isset($data['showTabC'])) $e->setShowTabC((bool)$data['showTabC']);
    if (isset($data['showTabD'])) $e->setShowTabD((bool)$data['showTabD']);

    return $e;
  }

  public function toArray(): array
  {
    return [
      'id'                    => $this->id,
      'type'                  => $this->type,
      'office'                => $this->office,
      'subject'               => $this->subject,
      'content'               => $this->content,
      'notes'                 => $this->notes,
      'showTabA'              => $this->showTabA,
      'showTabB'              => $this->showTabB,
      'showTabC'              => $this->showTabC,
      'showTabD'              => $this->showTabD,
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

  public function getType(): string
  {
    return $this->type;
  }
  public function setType(string $type): void
  {
    $this->type = $type;
  }

  public function getOffice(): ?string
  {
    return $this->office;
  }
  public function setOffice(string $v): void
  {
    $this->office = $v;
  }

  public function getSubject(): string
  {
    return $this->subject;
  }
  public function setSubject(string $v): void
  {
    $this->subject = $v;
  }

  public function getContent(): ?string
  {
    return $this->content;
  }
  public function setContent(string $v): void
  {
    $this->content = $v;
  }

  public function getNotes(): ?string
  {
    return $this->notes;
  }
  public function setNotes(string $v): void
  {
    $this->notes = $v;
  }

  public function getShowTabA(): bool
  {
    return $this->showTabA;
  }
  public function setShowTabA(bool $v): void
  {
    $this->showTabA = $v;
  }

  public function getShowTabB(): bool
  {
    return $this->showTabB;
  }
  public function setShowTabB(bool $v): void
  {
    $this->showTabB = $v;
  }

  public function getShowTabC(): bool
  {
    return $this->showTabC;
  }
  public function setShowTabC(bool $v): void
  {
    $this->showTabC = $v;
  }

  public function getShowTabD(): bool
  {
    return $this->showTabD;
  }
  public function setShowTabD(bool $v): void
  {
    $this->showTabD = $v;
  }
}
