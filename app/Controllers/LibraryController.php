<?php

namespace App\Controllers;

use App\Repositories\LibraryRepository;
use App\Entities\Library;

class LibraryController
{
  public function __construct(private LibraryRepository $repo) {}

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

  public function list(): void
  {
    $library = $this->repo->list();
    $data = array_map(fn(Library $p) => $p->toArray(), $library);

    $this->json(['success' => true, 'libraries' => $data]);
  }

  public function create(): void
  {
    $library = Library::fromArray($this->readJsonInput());
    $saved = $this->repo->create($library);

    $this->json(['success' => true, 'library' => $saved->toArray()], 200);
  }

  public function delete(int $id): void
  {
    $existing = $this->repo->find($id);
    if (!$existing) {
      $this->json(['success' => false, 'error' => 'Library non trouvé'], 404);
      return;
    }

    $this->repo->delete($id);

    $this->json(['success' => true]);
  }
}
