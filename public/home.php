<!DOCTYPE html>
<html lang="fr">

<head>

  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <title>Podo'logique</title>
  <link rel="icon" type="image/png" sizes="192x192" href=" assets/favicon.png" />

  <link rel="stylesheet" href=" css/bootstrap.min.css" />
  <link rel="stylesheet" href=" css/style.css" />
  <link rel="stylesheet" href=" css/report.css" />
  <link rel="manifest" href="manifest.json" />
</head>

<body>
  <!--------------------------------------------------------->
  <!------------------------- Header ------------------------>
  <!--------------------------------------------------------->
  <header class="fixed-top container-fluid">
    <div class="container header-container">
      <div class="row g-0 flex-nowrap justify-content-center align-items-center py-3">
        <div class="col-auto me-2">
          <img class="logo" src="assets/icon.png" alt="Logo Podo'logique" />
        </div>

        <div class="col-auto">
          <h1>Podo'logique</h1>
        </div>
      </div>

      <!--- Navbar ------->
      <?php include PUBLIC_PATH . '/includes/navigation/header/tab-list.html'; ?>
    </div>
  </header>

  <!--------------------------------------------------------->
  <!------------------------- Main -------------------------->
  <!--------------------------------------------------------->
  <main class="container main-container">
    <!--- Alert system ----->
    <?php include PUBLIC_PATH . '/includes/alert.html'; ?>
    <!--- Navbar content --->
    <?php include PUBLIC_PATH . '/includes/navigation/header/tab-content.php'; ?>
  </main>

  <script src="js/bootstrap.bundle.min.js"></script>

  <script src="js/app.js"></script>
  <script src="js/appData.js"></script>

  <script src="js/services/patient.js"></script>
  <script src="js/services/prescriber.js"></script>
  <script src="js/services/folder.js"></script>
  <script src="js/services/examination.js"></script>
  <script src="js/services/pdfParameter.js"></script>
  <script src="js/services/library.js"></script>

  <script src="js/components/reset.js"></script>
  <script src="js/components/searchPatient.js"></script>
  <script src="js/components/searchFolder.js"></script>

  <script src="js/components/navigation/header/library.js"></script>
  <script src="js/components/navigation/header/prescriber.js"></script>
  <script src="js/components/navigation/header/pdfParameter.js"></script>

  <script src="js/components/navigation/mainApp/anamnesis.js"></script>
  <script src="js/components/navigation/mainApp/folderManagment.js"></script>
  <script src="js/components/navigation/mainApp/examination.js"></script>
  <script src="js/components/navigation/mainApp/report.js"></script>

  <script src="js/alpinejs3-15-0cdn.min.js"></script>

  <!-- Test si le serveur est toujours lancé -->
  <script>
    setInterval(() => {
      fetch("/ping.php", {
          cache: "no-store"
        })
        .then(response => {
          if (!response.ok) throw new Error();
        })
        .catch(() => {
          window.location.href = "/home.html";
        });
    }, 2000);
  </script>

</body>

</html>
