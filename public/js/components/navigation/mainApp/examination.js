function examination() {
  return {
    folderId: null,
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
          first: "equipments",
          second: "equipmentsDetails",
        },
        column: {
          first: "Appareillage",
          second: "Details",
        },
      },
    ],
    suggestions: {
      localisation: [],
      observation: [],
      equipments: [],
      equipmentsDetails: [],
    },

    async loadFolder(folderId) {
      console.log("loadFolder examination.js");

      if (!folderId) {
        return;
      }

      this.folderId = folderId;

      const suggestions = await getAllSuggestion();
      for (let s of suggestions) {
        this.suggestions[s.name] = s.list;
      }

      const examinations = await getExaminationByFolder(folderId);
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

    getTemplateTab(name) {
      return this.templateTabs.find((tab) => tab.name === name);
    },

    isEditingRow(templateName) {
      return this.getTemplateTab(templateName).rows.some((r) => r.editing);
    },

    getSplitedInput(index, field, templateName) {
      const row = this.getTemplateTab(templateName).rows[index];
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
      const list = this.suggestions[suggestionListName] || [];
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
      const row = this.getTemplateTab(templateName).rows[index];
      row.editing = false;

      if (row.localisationInput.trim() == "" && row.observationInput.trim() == "") {
        if (row?.id != null) {
          await deleteExamination(row.id);
          customDispatch("update-folder", { folderId: this.folderId });

          return;
        } else {
          return;
        }
      }

      let data = {
        folder_id: this.folderId,
        name: templateName,
        localisation: row.localisationInput,
        observation: row.observationInput,
      };

      if (row?.id != null) {
        data.id = row.id;
        await updateExamination(data);
        customDispatch("update-folder", { folderId: this.folderId });

        return;
      }

      await createExamination(data);
      customDispatch("update-folder", { folderId: this.folderId });
    },

    async onDeleteRow(index, templateName) {
      const row = this.getTemplateTab(templateName).rows[index];
      await deleteExamination(row.id);
      customDispatch("update-folder", { folderId: this.folderId });
    },
  };
}
