<?php

namespace App\Entities;

class Library
{
  private ?int $id = null;
  private string $name;
  private string $value;

  public function __construct(string $name, string $value)
  {
    $this->name = $name;
    $this->value = $value;
  }

  public static function fromArray(array $data): self
  {
    $e = new self($data['name'], $data['value']);

    if (isset($data['id'])) $e->setId((int)$data['id']);

    return $e;
  }

  public function toArray(): array
  {
    return [
      'id'    => $this->id,
      'name'  => $this->name,
      'value' => $this->value,
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

  public function getName(): string
  {
    return $this->name;
  }
  public function setName(string $v): void
  {
    $this->name = $v;
  }

  public function getValue(): string
  {
    return $this->value;
  }
  public function setValue(string $v): void
  {
    $this->value = $v;
  }
}
