<div
  id="mainAppTabContent"
  class="tab-content py-3"
  x-data="examination()"
  x-on:create-patient.document="load($event.detail.folder)"
  x-on:select-patient.document="load(null)"
  x-on:close-patient.document="load(null)"
  x-on:select-folder.document="load($event.detail.folder)"
  x-on:update-examination.document="load($event.detail.folder)"
  x-on:update-suggestion.document="loadSuggestion()">

  <!-- Folder managment tab -->
  <div
    id="folderManagment"
    class="tab-pane fade"
    role="tabpanel"
    aria-labelledby="folderManagmentTab">
    <?php include PUBLIC_PATH . '/includes/navigation/mainApp/tabContent/folder-managment.html'; ?>
  </div>

  <!-- Anamnesis tab -->
  <div
    id="anamnesis"
    class="tab-pane fade show active"
    role="tabpanel"
    aria-labelledby="anamnesisTab">
    <?php include PUBLIC_PATH . '/includes/navigation/mainApp/tabContent/anamnesis.html'; ?>
  </div>

  <!-- Examination tabs : visual, palpatory, podoscopic, walk study, equipment plan  -->
  <template x-for="t in templateTabs" :key="t.name">
    <div :id="t.name" class="tab-pane fade" role="tabpanel" :aria-labelledby="t.name + 'Tab'">
      <div class="table-responsive">
        <table class="table table-striped table-hover">
          <colgroup>
            <col style="width: 30%" />
            <col style="width: 55%" />
            <col style="width: 15%" />
          </colgroup>
          <thead>
            <tr>
              <th x-text="t.column.first"></th>
              <th x-text="t.column.second"></th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <template x-for="(row, index) in t.rows" :key="index">
              <tr>
                <!-- LOCALISATION -->
                <td @click="if (!isEditingRow(t.name)) onEditRow(index, t.name, 'localisation')">
                  <template x-if="row.editing">
                    <div>
                      <textarea
                        class="form-control"
                        x-model="row.localisationInput"
                        @keydown.enter.prevent="onSaveRow(index, t.name)"
                        x-init="(row._refs ??= {}, row._refs.localisationInput = $el)"></textarea>

                      <!-- SUGGESTION -->
                      <ul
                        class="list-group w-100 z-10"
                        x-show="getSuggestions('localisation', row, t.suggestion.first).length > 0">
                        <template x-for="s in getSuggestions('localisation', row, t.suggestion.first)" :key="s">
                          <li
                            class="list-group-item list-group-item-action"
                            @click="selectSuggestion('localisation', row, s)">
                            <span x-text="s"></span>
                          </li>
                        </template>
                      </ul>
                    </div>
                  </template>

                  <template x-if="!row.editing">
                    <div>
                      <template x-for="part in getSplitedInput(index, 'localisation', t.name)" :key="part">
                        <div x-text="part"></div>
                      </template>
                    </div>
                  </template>
                </td>

                <!-- OBSERVATION -->
                <td @click="if (!isEditingRow(t.name)) onEditRow(index, t.name, 'observation')">
                  <template x-if="row.editing">
                    <div>
                      <textarea
                        class="form-control"
                        x-model="row.observationInput"
                        @keydown.enter.prevent="onSaveRow(index, t.name)"
                        x-init="(row._refs ??= {}, row._refs.observationInput = $el)"></textarea>

                      <!-- Suggestions -->
                      <ul
                        class="list-group w-100 z-10"
                        x-show="getSuggestions('observation', row, t.suggestion.second).length > 0">
                        <template x-for="s in getSuggestions('observation', row, t.suggestion.second)" :key="s">
                          <li
                            class="list-group-item list-group-item-action"
                            @click="selectSuggestion('observation', row, s)">
                            <span x-text="s"></span>
                          </li>
                        </template>
                      </ul>
                    </div>
                  </template>

                  <template x-if="!row.editing">
                    <div>
                      <template x-for="obs in getSplitedInput(index, 'observation', t.name)" :key="obs">
                        <div x-text="obs"></div>
                      </template>
                    </div>
                  </template>
                </td>

                <!-- ACTIONS -->
                <td class="text-center align-middle">
                  <template x-if="row.editing">
                    <img
                      src="assets/save.png"
                      alt="save icon"
                      class="icon-table-save"
                      @click="onSaveRow(index, t.name)" />
                  </template>

                  <template x-if="!row.editing">
                    <div :disabled="isEditingRow(t.name)" class="d-flex justify-content-around">
                      <img
                        src="assets/edit.png"
                        alt="edit icon"
                        class="icon-table-edit"
                        @click="onEditRow(index, t.name)" />
                      <img
                        x-show="!(index === t.rows.length - 1 && getSplitedInput(index, 'localisation', t.name).length === 0 && getSplitedInput(index, 'observation', t.name).length === 0)"
                        src="assets/trash.png"
                        alt="delete icon"
                        class="icon-table-delete"
                        @click="onDeleteRow(index, t.name)" />
                    </div>
                  </template>
                </td>
              </tr>
            </template>
          </tbody>
        </table>
      </div>
    </div>
  </template>

  <!-- Report tab -->
  <div
    id="report"
    class="tab-pane fade"
    role="tabpanel"
    aria-labelledby="reportTab">
    <?php include PUBLIC_PATH . '/includes/navigation/mainApp/tabContent/report.html'; ?>
  </div>
</div>
