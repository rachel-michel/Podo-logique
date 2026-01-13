function anamnesis() {
  return {
    patient: {
      id: null,
      gender: "Mme",
      lastname: "",
      firstname: "",
      dateOfBirth: "",
      phoneNumber: "",
      address: "",
      folderPrefix: "Consultation du",
      folderPrefixFormat: "prefixDate",
      weight: 0,
      height: 0,
      shoeSize: 0,
      job: "",
      physicalActivity: "",
      pathology: "",
      medicalHistory: "",
      notices: "",
      createdAt: null,
      updatedAt: null,
    },

    async loadPatient(patientId) {
      console.log("loadPatient anamnesis.js");

      if (!patientId) {
        this.resetPatient();
        return;
      }

      this.patient = await getPatient(patientId);
    },

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
      const isNewPatient = !this.patient.id;

      this.patient.createdAt = isNewPatient ? now.toLocaleString("fr-FR") : this.patient.createdAt;
      this.patient.updatedAt = isNewPatient ? null : now.toLocaleString("fr-FR");

      try {
        if (isNewPatient) {
          const patient = await createPatient({ ...this.patient });

          // Create new pdf parameters for the folder
          const globalPdfParameter = await getGlobalPdfParameter();
          const pdfParameter = await createPdfParameter({
            office: globalPdfParameter.office,
            prescriber_id: null,
            subject: globalPdfParameter.subject,
            notes: "",
            showTabA: globalPdfParameter.showTabA,
            showTabB: globalPdfParameter.showTabB,
            showTabC: globalPdfParameter.showTabC,
            showTabD: globalPdfParameter.showTabD,
          });

          // Create new folder
          const folderName = getFolderName(this.patient.folderPrefixFormat, this.patient.folderPrefix);
          const folder = await createFolder({
            patient_id: patient.id,
            pdf_parameter_id: pdfParameter.id,
            name: folderName,
          });

          // Reload all the tabs with the new values
          customDispatch("create-patient", { patientId: patient.id, folderId: folder.id });
          customDispatch("display-tab", { display: true }); // Show tabs
          customDispatch("notify", { message: "Patient ajouté", type: "alert-success" });
        } else {
          await updatePatient(this.patient.id, { ...this.patient });

          // Reload tab values
          customDispatch("update-patient", { patientId: this.patient.id, resetFolder: false });
          customDispatch("notify", { message: "Patient modifié", type: "alert-warning" });
        }
      } catch (err) {
        console.error("Erreur patient →", err);
        customDispatch("notify", {
          message: isNewPatient ? "Erreur lors de l'ajout du patient" : "Erreur lors de la modification du patient",
          type: "alert-danger",
        });
      }
    },

    resetPatient() {
      this.patient = {
        id: null,
        gender: "Mme",
        lastname: "",
        firstname: "",
        dateOfBirth: "",
        phoneNumber: "",
        address: "",
        folderPrefix: "Consultation du",
        folderPrefixFormat: "prefixDate",
        weight: 0,
        height: 0,
        shoeSize: 0,
        job: "",
        physicalActivity: "",
        pathology: "",
        medicalHistory: "",
        notices: "",
        createdAt: null,
        updatedAt: null,
      };
    },
  };
}
