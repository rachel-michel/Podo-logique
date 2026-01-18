function report() {
  return {
    prescriberList: [],
    isEdit: false,

    async init() {
      await this.loadPrescriber();
    },

    async loadPrescriber() {
      this.prescriberList = await getAllPrescriber();
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
      this.pdfParameter = await updatePdfParameter(this.pdfParameter);
      this.isEdit = false;
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
