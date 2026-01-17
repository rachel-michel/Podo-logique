<?php

namespace App\Controllers;

use App\Repositories\FolderRepository;
use App\Entities\Folder;

class FolderController
{
  public function __construct(
    private FolderRepository $repo
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

  public function listByPatient(int $id): void
  {
    $folders = $this->repo->findByPatient($id);

    $data = array_map(fn(Folder $f) => $f->toArray(), $folders);
    $this->json(['success' => true, 'folders' => $data]);
  }

  public function get(int $id): void
  {
    $folder = $this->repo->find($id);
    if (!$folder) {
      $this->json(['success' => false, 'error' => 'Dossier non trouvé'], 404);
      return;
    }

    $this->json(['success' => true, 'folder' => $folder->toArray()]);
  }

  public function create(): void
  {
    $input = $this->readJsonInput();

    $patientId = (int)($input['patient_id'] ?? 0);
    $pdfParameterId = (int)($input['pdf_parameter_id'] ?? 0);
    $name      = trim($input['name'] ?? '');

    if ($patientId <= 0 || $pdfParameterId <= 0 || $name === '') {
      $this->json(['success' => false, 'error' => 'patient_id, pdf_parameter_id et name sont obligatoires'], 400);
      return;
    }

    try {
      $folder = Folder::fromArray($input);
    } catch (\InvalidArgumentException $e) {
      $this->json(['success' => false, 'error' => $e->getMessage()], 400);
      return;
    }

    $saved = $this->repo->create($folder);

    $this->json(['success' => true, 'folder' => $saved->toArray()], 200);
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

    $folder = Folder::fromArray($merged);
    $folder->setId($id);

    $saved = $this->repo->update($folder);

    $this->json(['success' => true, 'folder' => $saved->toArray()]);
  }
}
