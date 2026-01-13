<?php

namespace App\Controllers;

use App\Repositories\PatientRepository;
use App\Entities\Patient;

class PatientController
{
  public function __construct(
    private PatientRepository $repo
  ) {}

  private function json(mixed $data, int $status = 200): void
  {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
  }

  private function readJsonInput(): array
  {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);

    return is_array($data) ? $data : [];
  }

  public function list(): void
  {
    $patients = $this->repo->list();
    $data = array_map(fn(Patient $p) => $p->toArray(), $patients);

    $this->json(['success' => true, 'patients' => $data]);
  }

  public function get(int $id): void
  {
    $patient = $this->repo->find($id);

    if (!$patient) {
      $this->json(['success' => false, 'error' => 'Patient non trouvé'], 404);
      return;
    }

    $this->json(['success' => true, 'patient' => $patient->toArray()]);
  }

  public function create(): void
  {
    $input = $this->readJsonInput();

    $lastname  = trim($input['lastname']  ?? '');
    $firstname = trim($input['firstname'] ?? '');

    if ($lastname === '' || $firstname === '') {
      $this->json(['success' => false, 'error' => 'lastname et firstname sont obligatoires'], 400);
      return;
    }

    $patient = Patient::fromArray($input);
    $saved   = $this->repo->create($patient);

    $this->json(['success' => true, 'patient' => $saved->toArray()], 201);
  }

  public function update(int $id): void
  {
    $existing = $this->repo->find($id);
    if (!$existing) {
      $this->json(['success' => false, 'error' => 'Patient non trouvé'], 404);
      return;
    }

    $input = $this->readJsonInput();

    // On part des données existantes, on écrase avec ce qui arrive
    $merged = array_merge($existing->toArray(), $input);
    $patient = Patient::fromArray($merged);
    $patient->setId($id); // sécurité

    $saved = $this->repo->update($patient);

    $this->json(['success' => true, 'patient' => $saved->toArray()]);
  }
}
