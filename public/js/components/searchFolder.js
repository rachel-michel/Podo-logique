function searchFolder() {
  return {
    getPatientFullname() {
      return this.patient.gender + " " + this.patient.lastname + " " + this.patient.firstname;
    },

    getFolderName() {
      return this.folder.name;
    },

    onSelectFolder(id) {
      if (!id) return;
      const folder = this.activeFolders.find((f) => f.id == id);
      if (!folder) return;

      this.selectFolder(folder);
    },

    async onAddFolder() {
      if (!this.patient) return;

      try {
        // Create new pdf parameters for the folder
        const pdfParameter = (this.pdfParameter = await createPdfParameter({
          office: this.globalPdfParameter.office,
          prescriberFullname: "",
          prescriberAddress: "",
          prescriberMail: "",
          prescriberPhoneNumber: "",
          subject: this.globalPdfParameter.subject,
          notes: "",
          showTabA: this.globalPdfParameter.showTabA,
          showTabB: this.globalPdfParameter.showTabB,
          showTabC: this.globalPdfParameter.showTabC,
          showTabD: this.globalPdfParameter.showTabD,
        }));

        // Create new folder
        const folderName = getFolderName(this.patient.folderPrefixFormat, this.patient.folderPrefix);
        const folder = await createFolder({
          patient_id: this.patient.id,
          pdf_parameter_id: this.pdfParameter.id,
          name: folderName,
        });

        this.addFolder(folder, pdfParameter);
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
