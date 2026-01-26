<?php

namespace App\Entities;

class Patient
{
  private ?int $id = null;
  private ?string $gender = null;
  private string $lastname;
  private string $firstname;
  private string $dateOfBirth;
  private ?string $phoneNumber = null;
  private ?string $address = null;
  private string $folderPrefix = 'Consultation du';
  private string $folderPrefixFormat = 'prefixDate';
  private ?float $weight = null;
  private ?float $height = null;
  private ?float $shoeSize = null;
  private ?string $job = null;
  private ?string $physicalActivity = null;
  private ?string $pathology = null;
  private ?string $medicalHistory = null;
  private ?string $notices = null;
  private ?string $lastDeliveryAt;
  private string $createdAt;
  private ?string $updatedAt = null;

  public function __construct(
    string $lastname,
    string $firstname
  ) {
    $this->lastname = $lastname;
    $this->firstname = $firstname;
    $now = date('c');
    $this->createdAt = $now;
  }

  public static function fromArray(array $data): self
  {
    $patient = new self(
      $data['lastname'] ?? '',
      $data['firstname'] ?? '',
      $data['dateOfBirth'] ?? ''
    );

    if (isset($data['id'])) $patient->setId((int)$data['id']);

    if (isset($data['gender'])) $patient->setGender($data['gender']);
    if (isset($data['dateOfBirth'])) $patient->setDateOfBirth($data['dateOfBirth']);
    if (isset($data['phoneNumber'])) $patient->setPhoneNumber($data['phoneNumber']);
    if (isset($data['address'])) $patient->setAddress($data['address']);
    if (isset($data['folderPrefix'])) $patient->setFolderPrefix($data['folderPrefix']);
    if (isset($data['folderPrefixFormat'])) $patient->setFolderPrefixFormat($data['folderPrefixFormat']);
    if (isset($data['weight'])) $patient->setWeight($data['weight']);
    if (isset($data['height'])) $patient->setHeight($data['height']);
    if (isset($data['shoeSize'])) $patient->setShoeSize($data['shoeSize']);
    if (isset($data['job'])) $patient->setJob($data['job']);
    if (isset($data['physicalActivity'])) $patient->setPhysicalActivity($data['physicalActivity']);
    if (isset($data['pathology'])) $patient->setPathology($data['pathology']);
    if (isset($data['medicalHistory'])) $patient->setMedicalHistory($data['medicalHistory']);
    if (isset($data['notices'])) $patient->setNotices($data['notices']);

    if (!empty($data['lastDeliveryAt'])) $patient->setLastDeliveryAt($data['lastDeliveryAt']);
    if (!empty($data['createdAt'])) $patient->setCreatedAt($data['createdAt']);
    if (!empty($data['updatedAt'])) $patient->setUpdatedAt($data['updatedAt']);

    return $patient;
  }

  public function toArray(): array
  {
    return [
      'id' => $this->id,
      'gender' => $this->gender,
      'lastname' => $this->lastname,
      'firstname' => $this->firstname,
      'dateOfBirth' => $this->dateOfBirth,
      'phoneNumber' => $this->phoneNumber,
      'address' => $this->address,
      'folderPrefix' => $this->folderPrefix,
      'folderPrefixFormat' => $this->folderPrefixFormat,
      'weight' => $this->weight,
      'height' => $this->height,
      'shoeSize' => $this->shoeSize,
      'job' => $this->job,
      'physicalActivity' => $this->physicalActivity,
      'pathology' => $this->pathology,
      'medicalHistory' => $this->medicalHistory,
      'notices' => $this->notices,
      'lastDeliveryAt' => $this->lastDeliveryAt,
      'createdAt' => $this->createdAt,
      'updatedAt' => $this->updatedAt,
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

  public function getGender(): ?string
  {
    return $this->gender;
  }
  public function setGender(string $gender): void
  {
    $this->gender = $gender;
  }

  public function getLastname(): string
  {
    return $this->lastname;
  }
  public function setLastname(string $lastname): void
  {
    $this->lastname = $lastname;
  }

  public function getFirstname(): string
  {
    return $this->firstname;
  }
  public function setFirstname(string $firstname): void
  {
    $this->firstname = $firstname;
  }

  public function getDateOfBirth(): string
  {
    return $this->dateOfBirth;
  }
  public function setDateOfBirth(string $date): void
  {
    $this->dateOfBirth = $date;
  }

  public function getPhoneNumber(): ?string
  {
    return $this->phoneNumber;
  }
  public function setPhoneNumber(string $phone): void
  {
    $this->phoneNumber = $phone;
  }

  public function getAddress(): ?string
  {
    return $this->address;
  }
  public function setAddress(string $address): void
  {
    $this->address = $address;
  }

  public function getFolderPrefix(): ?string
  {
    return $this->folderPrefix;
  }
  public function setFolderPrefix(string $prefix): void
  {
    $this->folderPrefix = $prefix;
  }

  public function getFolderPrefixFormat(): ?string
  {
    return $this->folderPrefixFormat;
  }
  public function setFolderPrefixFormat(string $format): void
  {
    $this->folderPrefixFormat = $format;
  }

  public function getWeight(): ?float
  {
    return $this->weight;
  }
  public function setWeight(float $weight): void
  {
    $this->weight = $weight;
  }

  public function getHeight(): ?float
  {
    return $this->height;
  }
  public function setHeight(float $height): void
  {
    $this->height = $height;
  }

  public function getShoeSize(): ?float
  {
    return $this->shoeSize;
  }
  public function setShoeSize(float $shoeSize): void
  {
    $this->shoeSize = $shoeSize;
  }

  public function getJob(): ?string
  {
    return $this->job;
  }
  public function setJob(string $job): void
  {
    $this->job = $job;
  }

  public function getPhysicalActivity(): ?string
  {
    return $this->physicalActivity;
  }
  public function setPhysicalActivity(string $activity): void
  {
    $this->physicalActivity = $activity;
  }

  public function getPathology(): ?string
  {
    return $this->pathology;
  }
  public function setPathology(string $pathology): void
  {
    $this->pathology = $pathology;
  }

  public function getMedicalHistory(): ?string
  {
    return $this->medicalHistory;
  }
  public function setMedicalHistory(string $history): void
  {
    $this->medicalHistory = $history;
  }

  public function getNotices(): ?string
  {
    return $this->notices;
  }
  public function setNotices(string $notices): void
  {
    $this->notices = $notices;
  }

  public function getLastDeliveryAt(): ?string
  {
    return $this->lastDeliveryAt;
  }
  public function setLastDeliveryAt(string $lastDeliveryAt): void
  {
    $this->lastDeliveryAt = $lastDeliveryAt;
  }

  public function getCreatedAt(): string
  {
    return $this->createdAt;
  }
  public function setCreatedAt(string $createdAt): void
  {
    $this->createdAt = $createdAt;
  }

  public function getUpdatedAt(): ?string
  {
    return $this->updatedAt;
  }
  public function setUpdatedAt(string $updatedAt): void
  {
    $this->updatedAt = $updatedAt;
  }
}
