<?php

namespace App\Controllers;

use App\Repositories\FollowUpNoteRepository;
use App\Entities\FollowUpNote;

class FollowUpNoteController
{
  public function __construct(
    private FollowUpNoteRepository $repo
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
    $followUpNotes = $this->repo->findByFolder($id);

    $data = array_map(fn(FollowUpNote $f) => $f->toArray(), $followUpNotes);
    $this->json(['success' => true, 'notes' => $data]);
  }

  public function create(): void
  {
    $input = $this->readJsonInput();

    $folderId     = (int)($input['folder_id'] ?? 0);
    $text         = trim($input['text'] ?? '');

    if ($folderId <= 0 || $text === '') {
      $this->json(['success' => false, 'error' => 'folder_id et text sont obligatoires'], 400);
      return;
    }

    try {
      $followUpNote = FollowUpNote::fromArray($input);
    } catch (\InvalidArgumentException $e) {
      $this->json(['success' => false, 'error' => $e->getMessage()], 400);
      return;
    }

    $saved = $this->repo->create($followUpNote);

    $this->json(['success' => true, 'note' => $saved->toArray()], 200);
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

    $followUpNote = FollowUpNote::fromArray($merged);
    $followUpNote->setId($id);

    $saved = $this->repo->update($followUpNote);

    $this->json(['success' => true, 'note' => $saved->toArray()]);
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
