<?php
// Todo remove file
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
}
