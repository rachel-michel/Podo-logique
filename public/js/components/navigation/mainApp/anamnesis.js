function anamnesis() {
  return {
    getFormatedDate(dateString) {
      if (!dateString) return "";
      const d = new Date(dateString);

      const datePart = new Intl.DateTimeFormat("fr-FR", {
        day: "2-digit",
        month: "2-digit",
        year: "numeric",
      }).format(d);

      const pad = (n) => n.toString().padStart(2, "0");
      const timePart = `${pad(d.getHours())}h${pad(d.getMinutes())}`;

      return `${datePart} à ${timePart}`;
    },

    async onSave() {
      const now = new Date();
      const isExistingPatient = this.patient.id;

      if (isExistingPatient) {
        try {
          this.patient.updatedAt = now.toISOString();

          const patient = await updatePatient(this.patient.id, { ...this.patient });
          this.patient = patient;
          this.patients = this.patients.map((p) => (p.id === patient.id ? patient : p));

          customDispatch("notify", { message: "Patient modifié", type: "alert-success" });
        } catch (err) {
          console.error("Erreur patient →", err);
          customDispatch("notify", { message: "Erreur lors de la modification du patient", type: "alert-danger" });
        }
      } else {
        try {
          this.patient.lastDeliveryAt = now.toLocaleDateString("fr-CA");

          const patient = await createPatient({ ...this.patient });
          this.patients.push(patient);

          // Create new pdf parameters for the folder
          const pdfParameter = await createPdfParameter({
            office: this.globalPdfParameter.office,
            prescriberFullname: "",
            prescriberAddress: "",
            prescriberMail: "",
            prescriberPhoneNumber: "",
            subject: this.globalPdfParameter.subject,
            content: this.globalPdfParameter.content,
            notes: "",
            showTabA: this.globalPdfParameter.showTabA,
            showTabB: this.globalPdfParameter.showTabB,
            showTabC: this.globalPdfParameter.showTabC,
            showTabD: this.globalPdfParameter.showTabD,
          });

          // Create new folder
          const folderName = getFolderName(patient.folderPrefixFormat, patient.folderPrefix);
          const folder = await createFolder({
            patient_id: patient.id,
            pdf_parameter_id: pdfParameter.id,
            name: folderName,
          });

          this.selectPatient(patient, pdfParameter, folder);

          this.displayTab = true;
          this.lockTab = false;

          customDispatch("notify", { message: "Patient ajouté", type: "alert-success" });
        } catch (err) {
          console.error("Erreur patient →", err);
          customDispatch("notify", { message: "Erreur lors de l'ajout du patient", type: "alert-danger" });
        }
      }
    },
  };
}
