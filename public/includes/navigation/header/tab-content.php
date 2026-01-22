<div class="tab-content py-3" id="headerTabContent">
  <!--------------------------------------------------------------->
  <!------------------------- Main App Tab ------------------------>
  <!--------------------------------------------------------------->

  <div x-data="appData()"
    class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0"
    x-on:update-global-pdf-parameter.document="updateGlobalPdfParameter($event.detail.globalPdfParameter)">

    <!--- Search patient --->
    <div class="pt-4 pb-2 border-bottom">
      <?php include PUBLIC_PATH . '/includes/search-patient.html'; ?>
    </div>
    <!--- Reset patient ---->
    <div
      class="row justify-content-end p-2"
      x-cloak
      x-show="patient?.id != null"
      x-data="reset()">
      <button class="btn btn-close" @click="onClose"></button>
    </div>

    <!--- Search patient folder ---->
    <div>
      <?php include PUBLIC_PATH . '/includes/search-folder.html'; ?>
    </div>

    <!--- Navigation tab list ------>
    <div>
      <?php include PUBLIC_PATH . '/includes/navigation/mainApp/tab-list.html'; ?>
    </div>

    <!--- Navigation tab content --->
    <div>
      <?php include PUBLIC_PATH . '/includes/navigation/mainApp/tab-content.php'; ?>
    </div>
  </div>

  <!--------------------------------------------------------------->
  <!------------------------- Library Tab ------------------------->
  <!--------------------------------------------------------------->
  <div
    class="tab-pane fade"
    id="prescriber-tab-pane"
    role="tabpanel"
    aria-labelledby="prescriber-tab"
    tabindex="0">
    <?php include PUBLIC_PATH . '/includes/navigation/header/tabContent/prescriber.html'; ?>
  </div>
  <!--------------------------------------------------------------->
  <!------------------------- Prescriber Tab ------------------------->
  <!--------------------------------------------------------------->
  <div
    class="tab-pane fade"
    id="library-tab-pane"
    role="tabpanel"
    aria-labelledby="library-tab"
    tabindex="0">
    <?php include PUBLIC_PATH . '/includes/navigation/header/tabContent/library.html'; ?>
  </div>
  <!--------------------------------------------------------------->
  <!------------------------- Pdf Parameter Tab ------------------->
  <!--------------------------------------------------------------->
  <div
    class="tab-pane fade"
    id="pdfparameter-tab-pane"
    role="tabpanel"
    aria-labelledby="pdfparameter-tab"
    tabindex="0">
    <?php include PUBLIC_PATH . '/includes/navigation/header/tabContent/pdf-parameter.html'; ?>
  </div>
  <!--------------------------------------------------------------->
  <!------------------------- Documentation Tab --------------------->
  <!----------------------------------------------------------------->
  <div
    class="tab-pane fade"
    id="documentation-tab-pane"
    role="tabpanel"
    aria-labelledby="documentation-tab"
    tabindex="0">
    <?php include PUBLIC_PATH . '/includes/navigation/header/tabContent/documentation.html'; ?>
  </div>
</div>
