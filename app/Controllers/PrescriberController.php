<?php

namespace App\Controllers;

use App\Repositories\PrescriberRepository;
use App\Entities\Prescriber;

class PrescriberController
{
  public function __construct(private PrescriberRepository $repo) {}

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
    $prescribers = $this->repo->list();
    $data = array_map(fn(Prescriber $p) => $p->toArray(), $prescribers);

    $this->json(['success' => true, 'prescribers' => $data]);
  }

  public function create(): void
  {
    $input = $this->readJsonInput();

    error_log("DEBUG: je passe ici");
    error_log(print_r($input['fullname'], true));

    if (trim($input['fullname']) == "") {
      $this->json(['success' => false, 'error' => 'fullname est obligatoire'], 400);
      return;
    }

    $prescriber = Prescriber::fromArray($input);
    $saved = $this->repo->create($prescriber);

    $this->json(['success' => true, 'prescriber' => $saved->toArray()], 201);
  }

  public function update(int $id): void
  {
    $existing = $this->repo->find($id);
    if (!$existing) {
      $this->json(['success' => false, 'error' => 'Prescriber non trouvé'], 404);
      return;
    }

    $input = $this->readJsonInput();

    $merged = array_merge($existing->toArray(), $input);
    $prescriber = Prescriber::fromArray($merged);
    $prescriber->setId($id);

    $saved = $this->repo->update($prescriber);

    $this->json(['success' => true, 'prescriber' => $saved->toArray()]);
  }

  public function delete(int $id): void
  {
    $existing = $this->repo->find($id);
    if (!$existing) {
      $this->json(['success' => false, 'error' => 'Dossier non trouvé'], 404);
      return;
    }

    $this->repo->delete($id);

    $this->json(['success' => true]);
  }
}
