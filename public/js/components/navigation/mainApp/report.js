function report() {
  return {
    pdfParameter: {},
    examinations: [],
    equipmentPlan: [],
    prescriberList: [],
    isEdit: false,

    async load(folder, pdfParameter) {
      if (!folder) {
        return;
      }

      if (pdfParameter) {
        if (folder.pdf_parameter_id === pdfParameter.id) this.pdfParameter = pdfParameter;
        else this.pdfParameter = await getPdfParameterByFolder(folder.id);
      } else {
        if (!this.pdfParameter.length) {
          this.pdfParameter = await getPdfParameterByFolder(folder.id);
        }
      }

      const examinations = await getExaminationByFolder(folder.id);
      this.equipmentPlan = examinations.filter((e) => e.name == "equipmentPlan").sort((a, b) => a.id - b.id);
      this.examinations = [
        {
          show: this.pdfParameter.showTabA,
          name: "Examen visuel",
          rows: examinations.filter((e) => e.name == "visualExamination").sort((a, b) => a.id - b.id),
        },
        {
          show: this.pdfParameter.showTabB,
          name: "Examen palpatoire",
          rows: examinations.filter((e) => e.name == "palpatoryExamination").sort((a, b) => a.id - b.id),
        },
        {
          show: this.pdfParameter.showTabC,
          name: "Examen podoscopique",
          rows: examinations.filter((e) => e.name == "podoscopicExamination").sort((a, b) => a.id - b.id),
        },
        {
          show: this.pdfParameter.showTabD,
          name: "Etude de la marche",
          rows: examinations.filter((e) => e.name == "walkStudy").sort((a, b) => a.id - b.id),
        },
      ];

      if (!this.prescriberList.length) {
        await this.loadPrescriber();
      }
    },

    async loadPrescriber() {
      this.prescriberList = await getAllPrescriber();
    },

    getPrescribers() {
      if (!this.prescriberList.length || this.pdfParameter.prescriberFullname.trim() == "") return [];
      if (this.prescriberList.find((p) => p.fullname === this.pdfParameter.prescriberFullname)) return [];

      return this.prescriberList
        .filter((p) => p.fullname.toLowerCase().includes(this.pdfParameter.prescriberFullname.toLowerCase()))
        .slice(0, 8);
    },

    selectPrescriber(prescriber) {
      this.pdfParameter.prescriberFullname = prescriber.fullname;
      this.pdfParameter.prescriberAddress = prescriber.address;
      this.pdfParameter.prescriberMail = prescriber.mail;
      this.pdfParameter.prescriberPhoneNumber = prescriber.phoneNumber;
    },

    getSplitedInput(row, field) {
      return row[field]
        .split(";")
        .map((s) => s.trim())
        .filter(Boolean);
    },

    async onSavePdfParameter() {
      await updatePdfParameter(this.pdfParameter);
      this.isEdit = false;
      this.load(this.folder, this.pdfParameter);
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

    async sendToMail() {
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
  };
}
