function examination() {
  return {
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
      const list = this.suggestions[suggestionListName] || [];
      return list.filter((s) => s.toLowerCase().includes(lastPart) && !parts.includes(s.toLowerCase())).slice(0, 8);
    },

    onSelectSuggestion(field, row, suggestion) {
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
          this.onRemoveRow(index, templateName);
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

      try {
        if (row?.id != null) {
          data.id = row.id;
          await updateExamination(data);
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
      } catch (err) {
        console.error("Erreur patient →", err);
        customDispatch("notify", {
          message: "Une erreur est survenue. Veuillez rafraichir la page",
          type: "alert-danger",
        });
      }
    },

    async onRemoveRow(index, templateName) {
      const tab = this.getTemplateTab(templateName);
      const row = tab.rows[index];

      try {
        await deleteExamination(row.id);
        tab.rows = tab.rows.filter((r) => r.id !== row.id);
      } catch (err) {
        console.error("Erreur patient →", err);
        customDispatch("notify", {
          message: "Une erreur est survenue. Veuillez rafraichir la page",
          type: "alert-danger",
        });
      }
    },
  };
}
