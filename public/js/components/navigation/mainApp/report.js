function report() {
  return {
    isEdit: false,

    getPrescribers() {
      if (
        !this.prescribers.length ||
        !Object.keys(this.pdfParameter).length ||
        this.pdfParameter.prescriberFullname.trim() == ""
      )
        return [];

      if (this.prescribers.find((p) => p.fullname === this.pdfParameter.prescriberFullname)) return [];

      return this.prescribers
        .filter((p) => p.fullname.toLowerCase().includes(this.pdfParameter.prescriberFullname.toLowerCase()))
        .slice(0, 8);
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

    onSelectPrescriber(prescriber) {
      this.pdfParameter.prescriberFullname = prescriber.fullname;
      this.pdfParameter.prescriberAddress = prescriber.address;
      this.pdfParameter.prescriberMail = prescriber.mail;
      this.pdfParameter.prescriberPhoneNumber = prescriber.phoneNumber;
    },

    async onEditPdfParameter() {
      try {
        this.pdfParameter = await updatePdfParameter(this.pdfParameter);
        this.isEdit = false;
      } catch (err) {
        console.error("Erreur patient →", err);
        customDispatch("notify", {
          message: "Une erreur est survenue. Veuillez rafraichir la page",
          type: "alert-danger",
        });
      }
    },

    async onSendToMail() {
      try {
        await this.htmlToClipboard();
        alert("Contenu copié. A vous de le coller (ctrl+v) dans le mail.");
      } catch (e) {
        console.error(e);
      }

      const destinataire = "medecin@example.com";
      const sujet = this.pdfParameter.subject || "Compte-rendu";
      const sujetEncoded = encodeURIComponent(sujet);

      const mailto = `mailto:${destinataire}?subject=${sujetEncoded}`;
      window.location.href = mailto;
    },

    async htmlToClipboard() {
      const node = this.$refs.pdfTemplate;
      const html = node.outerHTML;
      const text = node.innerText.trim(); // fallback

      if (navigator.clipboard && window.ClipboardItem) {
        const item = new ClipboardItem({
          "text/html": new Blob([html], { type: "text/html" }),
          "text/plain": new Blob([text], { type: "text/plain" }),
        });

        await navigator.clipboard.write([item]);
        return;
      }

      // Fallback
      if (navigator.clipboard && navigator.clipboard.writeText) {
        await navigator.clipboard.writeText(text);
        return;
      }

      alert("Fonctionnalité non prise en charge par le navigateur");
      return;
    },
  };
}
