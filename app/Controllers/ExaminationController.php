<?php

namespace App\Controllers;

use App\Repositories\ExaminationRepository;
use App\Entities\Examination;

class ExaminationController
{
  public function __construct(
    private ExaminationRepository $repo
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

  public function listByFolder(int $id): void
  {
    $examinations = $this->repo->findByFolder($id);

    $data = array_map(fn(Examination $f) => $f->toArray(), $examinations);
    $this->json(['success' => true, 'examinations' => $data]);
  }

  public function create(): void
  {
    $input = $this->readJsonInput();

    $folderId     = (int)($input['folder_id'] ?? 0);
    $name         = trim($input['name'] ?? '');

    if ($folderId <= 0 || $name === '') {
      $this->json(['success' => false, 'error' => 'folder_id et name sont obligatoires'], 400);
      return;
    }

    try {
      $examination = Examination::fromArray($input);
    } catch (\InvalidArgumentException $e) {
      $this->json(['success' => false, 'error' => $e->getMessage()], 400);
      return;
    }

    $saved = $this->repo->create($examination);

    $this->json(['success' => true, 'examination' => $saved->toArray()], 201);
  }

  public function update(int $id): void
  {
    $existing = $this->repo->find($id);
    if (!$existing) {
      $this->json(['success' => false, 'error' => 'Dossier non trouvé'], 404);
      return;
    }

    $input = $this->readJsonInput();
    $merged = array_merge($existing->toArray(), $input);

    $examination = Examination::fromArray($merged);
    $examination->setId($id);

    $saved = $this->repo->update($examination);

    $this->json(['success' => true, 'examination' => $saved->toArray()]);
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
