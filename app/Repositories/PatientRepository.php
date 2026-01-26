<?php

namespace App\Repositories;

use App\Entities\Patient;
use PDO;

class PatientRepository
{
  public function __construct(private PDO $pdo)
  {
    $this->initSchema();
  }

  private function initSchema(): void
  {
    $this->pdo->exec("
        CREATE TABLE IF NOT EXISTS patient (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            gender TEXT NULL,
            lastname TEXT NOT NULL,
            firstname TEXT NOT NULL,
            dateOfBirth TEXT NOT NULL,
            phoneNumber TEXT NULL,
            address TEXT NULL,
            folderPrefix TEXT NULL,
            folderPrefixFormat TEXT NULL,
            weight REAL NULL,
            height REAL NULL,
            shoeSize REAL NULL,
            job TEXT NULL,
            physicalActivity TEXT NULL,
            pathology TEXT NULL,
            medicalHistory TEXT NULL,
            notices TEXT NULL,
            lastDeliveryAt TEXT NULL,
            createdAt TEXT NOT NULL,
            updatedAt TEXT NULL
        )
    ");

    $this->pdo->exec("CREATE INDEX IF NOT EXISTS idx_patient_lastname ON patient(lastname)");
    $this->pdo->exec("CREATE INDEX IF NOT EXISTS idx_patient_firstname ON patient(firstname)");
  }

  public function list(): array
  {
    $stmt = $this->pdo->query("SELECT * FROM patient ORDER BY lastname, firstname");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $rows ? array_map(fn($r) => Patient::fromArray($r), $rows) : [];
  }

  public function find(int $id): ?Patient
  {
    $stmt = $this->pdo->prepare("SELECT * FROM patient WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row ? Patient::fromArray($row) : null;
  }

  public function create(Patient $patient): Patient
  {
    if ($patient->getId() !== null) {
      throw new \RuntimeException('PatientRepository::save() ne doit être utilisé que pour créer un patient sans id. Utilise update().');
    }

    $patient->setCreatedAt(date('c'));

    $stmt = $this->pdo->prepare("
        INSERT INTO patient (
            gender, lastname, firstname, dateOfBirth,
            phoneNumber, address, folderPrefix, folderPrefixFormat,
            weight, height, shoeSize, job,
            physicalActivity, pathology, medicalHistory, notices,
            lastDeliveryAt, createdAt, updatedAt
        ) VALUES (
            :gender, :lastname, :firstname, :dateOfBirth,
            :phoneNumber, :address, :folderPrefix, :folderPrefixFormat,
            :weight, :height, :shoeSize, :job,
            :physicalActivity, :pathology, :medicalHistory, :notices,
            :lastDeliveryAt, :createdAt, :updatedAt
        )
    ");

    $stmt->execute([
      ':gender'             => $patient->getGender(),
      ':lastname'           => $patient->getLastname(),
      ':firstname'          => $patient->getFirstname(),
      ':dateOfBirth'        => $patient->getDateOfBirth(),
      ':phoneNumber'        => $patient->getPhoneNumber(),
      ':address'            => $patient->getAddress(),
      ':folderPrefix'       => $patient->getFolderPrefix(),
      ':folderPrefixFormat' => $patient->getFolderPrefixFormat(),
      ':weight'             => $patient->getWeight(),
      ':height'             => $patient->getHeight(),
      ':shoeSize'           => $patient->getShoeSize(),
      ':job'                => $patient->getJob(),
      ':physicalActivity'   => $patient->getPhysicalActivity(),
      ':pathology'          => $patient->getPathology(),
      ':medicalHistory'     => $patient->getMedicalHistory(),
      ':notices'            => $patient->getNotices(),
      ':lastDeliveryAt'     => $patient->getLastDeliveryAt(),
      ':createdAt'          => $patient->getCreatedAt(),
      ':updatedAt'          => $patient->getUpdatedAt(),
    ]);

    $patient->setId((int)$this->pdo->lastInsertId());

    return $patient;
  }

  public function update(Patient $patient): Patient
  {
    if ($patient->getId() === null) {
      throw new \RuntimeException('PatientRepository::update() nécessite un patient avec id. Utilise save() pour créer.');
    }

    $patient->setUpdatedAt(date('c'));

    $stmt = $this->pdo->prepare("
        UPDATE patient SET
            gender            = :gender,
            lastname          = :lastname,
            firstname         = :firstname,
            dateOfBirth       = :dateOfBirth,
            phoneNumber       = :phoneNumber,
            address           = :address,
            folderPrefix      = :folderPrefix,
            folderPrefixFormat= :folderPrefixFormat,
            weight            = :weight,
            height            = :height,
            shoeSize          = :shoeSize,
            job               = :job,
            physicalActivity  = :physicalActivity,
            pathology         = :pathology,
            medicalHistory    = :medicalHistory,
            notices           = :notices,
            lastDeliveryAt    = :lastDeliveryAt,
            updatedAt         = :updatedAt
        WHERE id = :id
    ");

    $stmt->execute([
      ':gender'            => $patient->getGender(),
      ':lastname'          => $patient->getLastname(),
      ':firstname'         => $patient->getFirstname(),
      ':dateOfBirth'       => $patient->getDateOfBirth(),
      ':phoneNumber'       => $patient->getPhoneNumber(),
      ':address'           => $patient->getAddress(),
      ':folderPrefix'      => $patient->getFolderPrefix(),
      ':folderPrefixFormat' => $patient->getFolderPrefixFormat(),
      ':weight'            => $patient->getWeight(),
      ':height'            => $patient->getHeight(),
      ':shoeSize'          => $patient->getShoeSize(),
      ':job'               => $patient->getJob(),
      ':physicalActivity'  => $patient->getPhysicalActivity(),
      ':pathology'         => $patient->getPathology(),
      ':medicalHistory'    => $patient->getMedicalHistory(),
      ':notices'           => $patient->getNotices(),
      ':lastDeliveryAt'    => $patient->getLastDeliveryAt(),
      ':updatedAt'         => $patient->getUpdatedAt(),
      ':id'                => $patient->getId(),
    ]);

    return $patient;
  }

  public function delete(int $id): void
  {
    $stmt = $this->pdo->prepare("DELETE FROM patient WHERE id = :id");
    $stmt->execute([':id' => $id]);
  }
}
