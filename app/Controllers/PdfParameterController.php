<?php

namespace App\Controllers;

use App\Repositories\PdfParameterRepository;
use App\Entities\PdfParameter;

class PdfParameterController
{
  public function __construct(private PdfParameterRepository $repo) {}

  private function json(mixed $data, int $status = 200): void
  {
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
  }

  private function readJsonInput(): array
  {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);

    return is_array($data) ? $data : [];
  }

  public function getGlobal(): void
  {
    $item = $this->repo->findGlobal();

    if (!$item) {
      $this->json(['success' => false, 'error' => 'Paramètres PDF globaux introuvables'], 404);
      return;
    }

    $this->json(['success' => true, 'pdfParameter' => $item->toArray()]);
  }

  public function getByFolder(int $id): void
  {
    $item = $this->repo->findByFolder($id);

    if (!$item) {
      $this->json(['success' => false, 'error' => 'Introuvable'], 404);
      return;
    }

    $this->json(['success' => true, 'pdfParameter' => $item->toArray()]);
  }

  public function create(): void
  {
    $entity = PdfParameter::fromArray($this->readJsonInput());
    $saved = $this->repo->create($entity);

    $this->json(['success' => true, 'pdfParameter' => $saved->toArray()], 200);
  }

  public function update(int $id): void
  {
    $existing = $this->repo->find($id);
    if (!$existing) {
      $this->json(['success' => false, 'error' => 'Introuvable'], 404);
      return;
    }

    $data = array_merge($existing->toArray(), $this->readJsonInput());
    $entity = PdfParameter::fromArray($data);
    $entity->setId($id);

    $saved = $this->repo->update($entity);
    $this->json(['success' => true, 'pdfParameter' => $saved->toArray()]);
  }
}
