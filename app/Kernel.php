<?php

namespace App;

use App\Database\Connection;
use App\Controllers\PatientController;
use App\Repositories\PatientRepository;

use App\Controllers\FolderController;
use App\Repositories\FolderRepository;

use App\Controllers\ExaminationController;
use App\Repositories\ExaminationRepository;

use App\Controllers\PdfParameterController;
use App\Repositories\PdfParameterRepository;

use App\Controllers\LibraryController;
use App\Repositories\LibraryRepository;

use App\Controllers\PrescriberController;
use App\Repositories\PrescriberRepository;

class Kernel
{
  private \PDO $pdo;

  public function __construct(string $dbPath)
  {
    $this->pdo = Connection::getInstance($dbPath);
  }

  public function handleRequest(): void
  {
    $uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $method = $_SERVER['REQUEST_METHOD'];

    // Gestion “fake” de PUT/DELETE pour certains environnements (optionnel)
    if ($method === 'POST' && isset($_POST['_method'])) {
      $method = strtoupper($_POST['_method']);
    }

    $router = new Router();
    $patientController = new PatientController(new PatientRepository($this->pdo));
    $folderController = new FolderController(new FolderRepository($this->pdo));
    $examinationController = new ExaminationController(new ExaminationRepository($this->pdo));
    $pdfParamsController = new PdfParameterController(new PdfParameterRepository($this->pdo));
    $libraryController = new LibraryController(new LibraryRepository($this->pdo));
    $prescriberController = new PrescriberController(new PrescriberRepository($this->pdo));

    // ====== PATIENT ======

    $router->get('/api/patients', fn() => $patientController->list());
    $router->get('/api/patient/{id}', fn($p) => $patientController->get((int)$p['id']));
    $router->post('/api/patient', fn() => $patientController->create());
    $router->put('/api/patient/{id}', fn($p) => $patientController->update((int)$p['id']));

    // ====== FOLDER ======

    $router->get('/api/folders/patient/{id}', fn($p) => $folderController->listByPatient((int)$p['id']));
    $router->get('/api/folder/{id}', fn($p) => $folderController->get((int)$p['id']));
    $router->post('/api/folder', fn() => $folderController->create());
    $router->put('/api/folder/{id}', fn($p) => $folderController->update((int)$p['id']));

    // ====== EXAMINATION ======

    $router->get('/api/examinations/folder/{id}', fn($p) => $examinationController->listByFolder((int)$p['id']));
    $router->post('/api/examinations', fn() => $examinationController->create());
    $router->put('/api/examinations/{id}', fn($p) => $examinationController->update((int)$p['id']));
    $router->delete('/api/examinations/{id}', fn($p) => $examinationController->delete((int)$p['id']));

    // ====== PDF PARAMETER ======

    $router->get('/api/pdf-parameter/global', fn() => $pdfParamsController->getGlobal());
    $router->get('/api/pdf-parameter/folder/{id}', fn($p) => $pdfParamsController->getByFolder((int)$p['id']));
    $router->post('/api/pdf-parameter', fn() => $pdfParamsController->create());
    $router->put('/api/pdf-parameter/{id}', fn($p) => $pdfParamsController->update((int)$p['id']));

    // ====== LIBRARY ======

    $router->get('/api/libraries', fn() => $libraryController->list());
    $router->post('/api/library', fn() => $libraryController->create());
    $router->delete('/api/library/{id}', fn($p) => $libraryController->delete((int)$p['id']));

    // ====== PRESCRIBER ======

    $router->get('/api/prescribers', fn() => $prescriberController->list());
    $router->post('/api/prescriber', fn() => $prescriberController->create());
    $router->put('/api/prescriber/{id}', fn($p) => $prescriberController->update((int)$p['id']));
    $router->delete('/api/prescriber/{id}', fn($p) => $prescriberController->delete((int)$p['id']));

    // ====== FRONT ======

    $router->get('/', fn() => require PUBLIC_PATH . '/home.php');

    // Dispatch
    $router->dispatch($method, $uri);
  }
}
