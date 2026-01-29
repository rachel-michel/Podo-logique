<div class="tab-content py-3" id="headerTabContent">
  <!--------------------------------------------------------------->
  <!------------------------- Main App Tab ------------------------>
  <!--------------------------------------------------------------->

  <div x-data="appData()"
    class="tab-pane fade show active" id="homeTab-pane" role="tabpanel" aria-labelledby="homeTab" tabindex="0"
    x-on:select-patient.document="selectPatient($event.detail.patient)"
    x-on:add-prescriber.document="updatePrescriber('add', $event.detail.prescriber)"
    x-on:update-prescriber.document="updatePrescriber('update', $event.detail.prescriber)"
    x-on:remove-prescriber.document="updatePrescriber('remove', $event.detail.prescriber)"
    x-on:add-suggestion.document="updateSuggestion('add', $event.detail.suggestion)"
    x-on:remove-suggestion.document="updateSuggestion('remove', $event.detail.suggestion)"
    x-on:update-global-pdf-parameter.document="updateGlobalPdfParameter($event.detail.globalPdfParameter)">

    <!--- Search patient --->
    <div class="pb-2 border-bottom">
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
  <!------------------------- Prescriber Tab ---------------------->
  <!--------------------------------------------------------------->
  <div
    class="tab-pane fade"
    id="prescriberTab-pane"
    role="tabpanel"
    aria-labelledby="prescriberTab"
    tabindex="0">
    <?php include PUBLIC_PATH . '/includes/navigation/header/tabContent/prescriber.html'; ?>
  </div>
  <!--------------------------------------------------------------->
  <!------------------------- Library Tab ------------------------->
  <!--------------------------------------------------------------->
  <div
    class="tab-pane fade"
    id="libraryTab-pane"
    role="tabpanel"
    aria-labelledby="libraryTab"
    tabindex="0">
    <?php include PUBLIC_PATH . '/includes/navigation/header/tabContent/library.html'; ?>
  </div>
  <!--------------------------------------------------------------->
  <!------------------------- Pdf Parameter Tab ------------------->
  <!--------------------------------------------------------------->
  <div
    class="tab-pane fade"
    id="pdfparameterTab-pane"
    role="tabpanel"
    aria-labelledby="pdfparameterTab"
    tabindex="0">
    <?php include PUBLIC_PATH . '/includes/navigation/header/tabContent/pdf-parameter.html'; ?>
  </div>
  <!--------------------------------------------------------------->
  <!------------------------- Reminder Tab ------------------->
  <!--------------------------------------------------------------->
  <div
    class="tab-pane fade"
    id="reminderTab-pane"
    role="tabpanel"
    aria-labelledby="reminderTab"
    tabindex="0">
    <?php include PUBLIC_PATH . '/includes/navigation/header/tabContent/reminder.html'; ?>
  </div>
  <!----------------------------------------------------------------->
  <!------------------------- Documentation Tab --------------------->
  <!----------------------------------------------------------------->
  <div
    class="tab-pane fade"
    id="documentationTab-pane"
    role="tabpanel"
    aria-labelledby="documentationTab"
    tabindex="0">
    <?php include PUBLIC_PATH . '/includes/navigation/header/tabContent/documentation.html'; ?>
  </div>
</div>
