function report() {
  return {
    prescriberList: [],
    isEdit: false,

    async init() {
      this.prescriberList = this.prescribers;
    },

    async reloadPrescriber(action, prescriber) {
      if (action === "add") {
        this.prescriberList.push(prescriber);
      }

      if (action === "update") {
        this.prescriberList = this.prescriberList.map((p) => (p.id === prescriber.id ? prescriber : p));
      }

      if (action === "remove") {
        this.prescriberList = this.prescriberList.filter((p) => p.id !== prescriber.id);
      }
    },

    getPrescribers() {
      if (
        !this.prescriberList.length ||
        !Object.keys(this.pdfParameter).length ||
        this.pdfParameter.prescriberFullname.trim() == ""
      )
        return [];
      if (this.prescriberList.find((p) => p.fullname === this.pdfParameter.prescriberFullname)) return [];

      return this.prescriberList
        .filter((p) => p.fullname.toLowerCase().includes(this.pdfParameter.prescriberFullname.toLowerCase()))
        .slice(0, 8);
    },

    getSplitedInput(row, field) {
      console.log(row, this.reportExamination);
      return row[field]
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
        console.log(this.pdfParameter);
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
