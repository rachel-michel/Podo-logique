function examination() {
  return {
    folder: null,
    suggestionList: null,
    templateTabs: [
      {
        name: "visualExamination",
        rows: [],
        suggestion: {
          first: "localisation",
          second: "observation",
        },
        column: {
          first: "Localisation",
          second: "Observation",
        },
      },
      {
        name: "palpatoryExamination",
        rows: [],
        suggestion: {
          first: "localisation",
          second: "observation",
        },
        column: {
          first: "Localisation",
          second: "Observation",
        },
      },
      {
        name: "podoscopicExamination",
        rows: [],
        suggestion: {
          first: "localisation",
          second: "observation",
        },
        column: {
          first: "Localisation",
          second: "Observation",
        },
      },
      {
        name: "walkStudy",
        rows: [],
        suggestion: {
          first: "localisation",
          second: "observation",
        },
        column: {
          first: "Localisation",
          second: "Observation",
        },
      },
      {
        name: "equipmentPlan",
        rows: [],
        suggestion: {
          first: "equipmentList",
          second: "equipmentDetail",
        },
        column: {
          first: "Appareillage",
          second: "Details",
        },
      },
    ],

    async load(folder) {
      if (!folder) {
        this.folder = null;
        return;
      }

      this.folder = folder;

      if (!this.suggestionList) {
        this.loadSuggestion();
      }

      const examinations = await getExaminationByFolder(folder.id);
      let examinationList = {
        visualExamination: examinations
          .filter((e) => e.name == "visualExamination")
          .map((a) => ({
            id: a.id,
            editing: false,
            _refs: {},
            localisationInput: a.localisation,
            observationInput: a.observation,
          }))
          .sort((a, b) => a.id - b.id),
        palpatoryExamination: examinations
          .filter((e) => e.name == "palpatoryExamination")
          .map((a) => ({
            id: a.id,
            editing: false,
            _refs: {},
            localisationInput: a.localisation,
            observationInput: a.observation,
          }))
          .sort((a, b) => a.id - b.id),
        podoscopicExamination: examinations
          .filter((e) => e.name == "podoscopicExamination")
          .map((a) => ({
            id: a.id,
            editing: false,
            _refs: {},
            localisationInput: a.localisation,
            observationInput: a.observation,
          }))
          .sort((a, b) => a.id - b.id),
        walkStudy: examinations
          .filter((e) => e.name == "walkStudy")
          .map((a) => ({
            id: a.id,
            editing: false,
            _refs: {},
            localisationInput: a.localisation,
            observationInput: a.observation,
          }))
          .sort((a, b) => a.id - b.id),
        equipmentPlan: examinations
          .filter((e) => e.name == "equipmentPlan")
          .map((a) => ({
            id: a.id,
            editing: false,
            _refs: {},
            localisationInput: a.localisation,
            observationInput: a.observation,
          }))
          .sort((a, b) => a.id - b.id),
      };

      for (let template of this.templateTabs) {
        template.rows = examinationList[template.name];
        template.rows.push({
          editing: false,
          _refs: {},
          localisationInput: "",
          observationInput: "",
        });
      }
    },

    async loadSuggestion() {
      if (!this.folder) return;

      const suggestions = await getAllSuggestion();
      this.suggestionList = {
        localisation: suggestions.filter((s) => s.name == "localisation").map((s) => s.value),
        observation: suggestions.filter((s) => s.name == "observation").map((s) => s.value),
        equipmentList: suggestions.filter((s) => s.name == "equipmentList").map((s) => s.value),
        equipmentDetail: suggestions.filter((s) => s.name == "equipmentDetail").map((s) => s.value),
      };
    },

    getTemplateTab(name) {
      return this.templateTabs.find((tab) => tab.name === name);
    },

    isEditingRow(templateName) {
      return this.getTemplateTab(templateName).rows.some((r) => r.editing);
    },

    getSplitedInput(index, field, templateName) {
      const row = this.getTemplateTab(templateName).rows[index];
      if (!row) return [];
      return row[`${field}Input`]
        .split(";")
        .map((s) => s.trim())
        .filter(Boolean);
    },

    getSuggestions(field, row, suggestionListName) {
      const input = row[`${field}Input`].toLowerCase().trim();
      if (!input) return [];

      // Get the last part of input
      const parts = input
        .split(";")
        .map((p) => p.trim())
        .filter(Boolean);
      const lastPart = parts[parts.length - 1] || "";

      // Filter suggets with every part and current entry
      const list = this.suggestionList[suggestionListName] || [];
      return list.filter((s) => s.toLowerCase().includes(lastPart) && !parts.includes(s.toLowerCase())).slice(0, 8);
    },

    selectSuggestion(field, row, suggestion) {
      // Replace last part with suggest
      let parts = row[`${field}Input`].split(";");
      parts[parts.length - 1] = " " + suggestion + "; ";

      // Re construct input content
      row[`${field}Input`] = parts.join(";").trim();

      // Replace cursor in input
      this.$nextTick(() => {
        row._refs?.[`${field}Input`]?.focus();
      });
    },

    onEditRow(index, templateName, field) {
      this.getTemplateTab(templateName).rows[index].editing = true;
      if (!field) return;

      this.$nextTick(() => {
        this.getTemplateTab(templateName).rows[index]._refs?.[`${field}Input`]?.focus();
      });
    },

    async onSaveRow(index, templateName) {
      const templateTab = this.getTemplateTab(templateName);
      const row = templateTab.rows[index];
      row.editing = false;

      if (row.localisationInput.trim() == "" && row.observationInput.trim() == "") {
        if (row?.id != null) {
          await this.onDeleteRow(index, templateName);
          return;
        } else {
          return;
        }
      }

      let data = {
        folder_id: this.folder.id,
        name: templateName,
        localisation: row.localisationInput,
        observation: row.observationInput,
      };

      if (row?.id != null) {
        data.id = row.id;
        await updateExamination(data);
        customDispatch("update-examination", { folder: this.folder });

        return;
      }

      const newRow = await createExamination(data);
      row.id = newRow.id;
      templateTab.rows.push({
        editing: false,
        _refs: {},
        localisationInput: "",
        observationInput: "",
      });

      customDispatch("update-examination", { folder: this.folder });
    },

    async onDeleteRow(index, templateName) {
      const tab = this.getTemplateTab(templateName);
      const row = tab.rows[index];

      await deleteExamination(row.id);

      tab.rows = tab.rows.filter((r) => r.id !== row.id);

      customDispatch("update-examination", { folder: this.folder });
    },
  };
}
