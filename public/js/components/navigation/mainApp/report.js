function report() {
  return {
    getPrescribers() {
      const normalize = (str) =>
        (str || "")
          .normalize("NFD")
          .replace(/[\u0300-\u036f]/g, "")
          .toLowerCase()
          .trim();

      if (
        !this.prescribers.length ||
        !Object.keys(this.pdfParameter).length ||
        normalize(this.pdfParameter.prescriberFullname) === ""
      ) {
        return [];
      }

      const query = normalize(this.pdfParameter.prescriberFullname);

      // ne pas proposer si le nom saisi correspond déjà exactement à un prescripteur (sans accents / casse)
      if (this.prescribers.some((p) => normalize(p.fullname) === query)) return [];

      return this.prescribers.filter((p) => normalize(p.fullname).includes(query)).slice(0, 8);
    },

    getEquipmentPlan() {
      return this.templateTabs
        .find((e) => e.name === "equipmentPlan")
        .rows.filter((row) => row.localisationInput.trim() !== "" || row.observationInput.trim() !== "");
    },

    isShowAtLeastOneExamination() {
      return (
        this.pdfParameter.showTabA ||
        this.pdfParameter.showTabB ||
        this.pdfParameter.showTabC ||
        this.pdfParameter.showTabD
      );
    },

    isEquipmentInclude(equipmentName) {
      const rows = this.templateTabs.find((e) => e.name === "equipmentPlan").rows;
      return rows.filter((equipment) => equipment.localisationInput.includes(equipmentName)).length;
    },

    getExaminations() {
      return [
        {
          show: this.pdfParameter.showTabA,
          name: "Examen visuel",
          rows: this.templateTabs
            .find((e) => e.name === "visualExamination")
            .rows.filter((row) => row.localisationInput.trim() !== "" || row.observationInput.trim() !== ""),
        },
        {
          show: this.pdfParameter.showTabB,
          name: "Examen palpatoire",
          rows: this.templateTabs
            .find((e) => e.name === "palpatoryExamination")
            .rows.filter((row) => row.localisationInput.trim() !== "" || row.observationInput.trim() !== ""),
        },
        {
          show: this.pdfParameter.showTabC,
          name: "Examen podoscopique",
          rows: this.templateTabs
            .find((e) => e.name === "podoscopicExamination")
            .rows.filter((row) => row.localisationInput.trim() !== "" || row.observationInput.trim() !== ""),
        },
        {
          show: this.pdfParameter.showTabD,
          name: "Etude de la marche",
          rows: this.templateTabs
            .find((e) => e.name === "walkStudy")
            .rows.filter((row) => row.localisationInput.trim() !== "" || row.observationInput.trim() !== ""),
        },
      ];
    },

    getSplitedInput(row, field) {
      return row[field + "Input"]
        .split(";")
        .map((s) => s.trim())
        .filter(Boolean);
    },

    replaceVariable(text) {
      if (!text || !text.trim().length) return "";

      const now = new Date();
      const birthday = new Date(this.patient.dateOfBirth);
      const createFolder = new Date(this.folder.createdAt);

      const map = {
        genre: this.patient.gender,
        nom_complet: [this.patient.lastname, this.patient.firstname].filter(Boolean).join(" "),
        nom: this.patient.lastname,
        date_de_naissance: birthday.toLocaleDateString("fr-FR"),
        date_creation_dossier: createFolder.toLocaleDateString("fr-FR"),
        date_aujourdhui: now.toLocaleDateString("fr-FR"),
      };

      return text.replace(/\{([a-zA-Z0-9_]+)\}/g, (match, varName) => {
        if (!(varName in map)) return match;
        return String(map[varName] ?? "");
      });
    },

    formatDateFR(d) {
      const date = new Date(d);
      return date.toLocaleDateString("fr-FR");
    },

    onSelectPrescriber(prescriber) {
      this.pdfParameter.prescriberFullname = prescriber.fullname;
      this.pdfParameter.prescriberAddress = prescriber.address;
      this.pdfParameter.prescriberMail = prescriber.mail;
      this.pdfParameter.prescriberPhoneNumber = prescriber.phoneNumber;
    },

    async onEditPdfParameter() {
      try {
        this.pdfParameter = await updatePdfParameter(this.pdfParameter);
        customDispatch("notify", { message: "Les modifications ont bien été prises en compte", type: "alert-success" });
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
