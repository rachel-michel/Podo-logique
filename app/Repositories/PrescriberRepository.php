<?php
// Todo remove file

namespace App\Repositories;

use App\Entities\Prescriber;
use PDO;

class PrescriberRepository
{
  public function __construct(private PDO $pdo)
  {
    $this->initSchema();
  }

  private function initSchema(): void
  {
    $this->pdo->exec("DROP TABLE IF EXISTS prescriber");
  }
}
