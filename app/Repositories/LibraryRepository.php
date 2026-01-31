<?php

namespace App\Repositories;

use App\Entities\Library;
use PDO;

class LibraryRepository
{
  public function __construct(private PDO $pdo)
  {
    $this->initSchema();
  }

  private function initSchema(): void
  {
    $this->pdo->exec("
        CREATE TABLE IF NOT EXISTS library (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            value TEXT NOT NULL
        )
    ");

    $stmt = $this->pdo->query("SELECT COUNT(*) AS c FROM library");
    $count = (int)($stmt->fetch(PDO::FETCH_ASSOC)['c'] ?? 0);

    if ($count === 0) {
      $this->pdo->exec("
        INSERT INTO library (name, value) VALUES
        ('localisation', 'pieds (bilatéral)'),
        ('localisation', 'pied gauche'),
        ('localisation', 'pied droit'),
        ('localisation', 'chevilles (bilatéral)'),
        ('localisation', 'cheville gauche'),
        ('localisation', 'cheville droite'),
        ('localisation', 'genoux (bilatéral)'),
        ('localisation', 'genou gauche'),
        ('localisation', 'genou droit'),
        ('localisation', 'jambes (bilatéral)'),
        ('localisation', 'jambe droite'),
        ('localisation', 'jambe gauche'),
        ('localisation', 'bassin'),
        ('localisation', 'colonne vertébrale'),
        ('localisation', 'épaules'),

        ('observation', 'pied grec'),
        ('observation', 'pied égyptien'),
        ('observation', 'pied carré'),
        ('observation', 'avant-pied rond'),
        ('observation', 'avant-pied large'),
        ('observation', 'hallux valgus'),
        ('observation', 'quintus varus'),
        ('observation', 'kératose sous M1'),
        ('observation', 'kératose sous M5'),
        ('observation', 'kératose à l''intérieur du talon'),
        ('observation', 'kératose à l''extérieur du talon'),

        ('observation', 'articulations souples - réductible'),
        ('observation', 'articulations raides - irréductible'),
        ('observation', 'douleurs entre les têtes métatarsiennes'),
        ('observation', 'douleurs sur la tête métatarsienne'),
        ('observation', 'douleurs sur le trajet du muscle tibial antérieur (face dorsale du pied)'),
        ('observation', 'douleurs sur le trajet du muscle extenseur des orteils (face dorsale du pied)'),
        ('observation', 'douleurs sur le trajet du muscle tibial postérieur (en arrière de la malléole interne, sous le naviculaire)'),
        ('observation', 'douleurs à la base du 5ème métatarse : terminaison du muscle court fibulaire'),
        ('observation', 'douleurs à la base du 1er métatarse : terminaison du muscle long fibulaire'),
        ('observation', 'douleurs sur le trajet de l''aponévrose plantaire'),
        ('observation', 'douleurs sur le trajet du tendon d''achille'),
        ('observation', 'douleurs au niveau de l''épine calcanéenne'),

        ('observation', 'pied plat'),
        ('observation', 'pied creux'),
        ('observation', 'valgus calcanéen'),
        ('observation', 'varus calcanéen'),
        ('observation', 'affaissement du naviculaire'),
        ('observation', 'affaissement du pied droit'),
        ('observation', 'affaissement du pied gauche'),
        ('observation', 'genu varum'),
        ('observation', 'genu valgum'),
        ('observation', 'genu recurvatum'),
        ('observation', 'genu flessum'),
        ('observation', 'alignement des genoux physiologiques'),
        ('observation', 'suspicion d''une inégalité de longueur de membre'),
        ('observation', 'bascule à droite'),
        ('observation', 'bascule à gauche'),
        ('observation', 'antéversion'),
        ('observation', 'rétroversion'),
        ('observation', 'vrille du bassin : antéversion à droite et rétroversion à gauche'),
        ('observation', 'vrille du bassin : antéversion à gauche et rétroversion à droite'),

        ('observation', 'angle de fick fermé'),
        ('observation', 'angle de fick ouvert'),
        ('observation', 'angle de fick physiologique'),
        ('observation', 'attaque talonnière franche'),
        ('observation', 'pas d''attaque talonnière'),
        ('observation', 'phase digitigrade absente - pas ou peu de propulsion sur l''avant pied'),
        ('observation', 'hyper appui côté M5'),
        ('observation', 'hyper appui de l''hallux'),
        ('observation', 'orteils en griffe'),

        ('observation', 'recherche d''équilibre : faiblesse des muscles stabilistateurs'),
        ('observation', 'appuis stable : bon maintien des muscles stabilisateurs'),

        ('equipmentList', 'Orthèses plantaires'),
        ('equipmentList', 'Anneau neutre'),
        ('equipmentList', 'Anneau pronateur'),
        ('equipmentList', 'Anneau supinateur'),
        ('equipmentList', 'Arciforme'),
        ('equipmentList', 'ARCM - appui retro capital médian'),
        ('equipmentList', 'BAC - barre antéro capitale totale'),
        ('equipmentList', 'Décharge de l''hallux'),
        ('equipmentList', 'Décharge des orteils 1-2'),
        ('equipmentList', 'Décharge du 2eme orteil'),
        ('equipmentList', 'Décharge des orteils 2-3'),
        ('equipmentList', 'Décharge des orteils 2-3-4'),
        ('equipmentList', 'Décharge du 3eme orteil'),
        ('equipmentList', 'Décharge des orteils 3-4'),
        ('equipmentList', 'Décharge des orteils 3-4-5'),
        ('equipmentList', 'Décharge du 4eme orteil'),
        ('equipmentList', 'Décharge des orteils 4-5'),
        ('equipmentList', 'Décharge du 5eme orteil'),
        ('equipmentList', 'Barre sous diaphysaire'),
        ('equipmentList', 'BRC - Barre rétro capital'),
        ('equipmentList', 'CPP - coin pronateur postérieur'),
        ('equipmentList', 'CSP - coin supinateur postérieur'),
        ('equipmentList', 'EPA - élément pronateur antérieur'),
        ('equipmentList', 'EPP - élément pronateur postérieur'),
        ('equipmentList', 'EPT - élément pronateur total'),
        ('equipmentList', 'ESP - élément supinateur postérieur'),
        ('equipmentList', 'Hémicoupole de décharge de l''hallux'),
        ('equipmentList', 'SAC - sous antéro capital court'),
        ('equipmentList', 'SAC - sous antéro capital long'),
        ('equipmentList', 'Sous cuboïdien'),
        ('equipmentList', 'Sous naviculaire'),
        ('equipmentList', 'Talonnette'),
        ('equipmentList', 'Voûte'),

        ('equipmentDetail', 'Liège beige'),
        ('equipmentDetail', 'Liège latex orange'),
        ('equipmentDetail', 'Mousse EVA verte'),
        ('equipmentDetail', 'Mousse EVA grise'),
        ('equipmentDetail', 'Base liège beige'),
        ('equipmentDetail', 'Base liège bleu'),
        ('equipmentDetail', 'Recouvrement sport orange'),
        ('equipmentDetail', 'Recouvrement sport bleu pétrol'),
        ('equipmentDetail', 'Recouvrement diabétique beige'),
        ('equipmentDetail', 'Recouvrement doux léopard')
      ");
    }
  }

  public function list(): array
  {
    $stmt = $this->pdo->query("SELECT * FROM library");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $rows ? array_map(fn($r) => Library::fromArray($r), $rows) : [];
  }

  public function find(int $id): ?Library
  {
    $stmt = $this->pdo->prepare("SELECT * FROM library WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row ? Library::fromArray($row) : null;
  }

  public function create(Library $library): Library
  {
    if ($library->getId() !== null) {
      throw new \RuntimeException('LibraryRepository::create() ne doit être utilisé que pour créer (id null). Utilise update().');
    }

    $stmt = $this->pdo->prepare("
        INSERT INTO library (
            name, value
        ) VALUES (
            :name, :value
        )
    ");

    $stmt->execute([
      ':name'  => $library->getName(),
      ':value' => $library->getValue(),
    ]);

    $library->setId((int)$this->pdo->lastInsertId());

    return $library;
  }

  public function delete(int $id): void
  {
    $stmt = $this->pdo->prepare("DELETE FROM library WHERE id = :id");
    $stmt->execute([':id' => $id]);
  }
}
