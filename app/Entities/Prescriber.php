<?php

namespace App\Entities;

class Prescriber
{
  private ?int $id = null;
  private string $fullname;
  private ?string $address = null;
  private ?string $mail = null;
  private ?string $phoneNumber = null;

  public function __construct(string $fullname)
  {
    $this->fullname = $fullname;
  }

  public static function fromArray(array $data): self
  {
    $prescriber = new self($data['fullname'] ?? '');

    if (isset($data['id'])) $prescriber->setId((int)$data['id']);
    if (isset($data['address'])) $prescriber->setAddress($data['address']);
    if (isset($data['mail'])) $prescriber->setMail($data['mail']);
    if (isset($data['phoneNumber'])) $prescriber->setPhoneNumber($data['phoneNumber']);

    return $prescriber;
  }

  public function toArray(): array
  {
    return [
      'id' => $this->id,
      'fullname' => $this->fullname,
      'address' => $this->address,
      'mail' => $this->mail,
      'phoneNumber' => $this->phoneNumber
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

  public function getFullname(): string
  {
    return $this->fullname;
  }
  public function setFullname(string $fullname): void
  {
    $this->fullname = $fullname;
  }

  public function getAddress(): ?string
  {
    return $this->address;
  }
  public function setAddress(string $address): void
  {
    $this->address = $address;
  }

  public function getMail(): ?string
  {
    return $this->mail;
  }
  public function setMail(string $mail): void
  {
    $this->mail = $mail;
  }

  public function getPhoneNumber(): ?string
  {
    return $this->phoneNumber;
  }
  public function setPhoneNumber(string $phoneNumber): void
  {
    $this->phoneNumber = $phoneNumber;
  }
}
