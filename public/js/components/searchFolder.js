function searchFolder() {
  return {
    patient: {},
    folder: {},
    folderPatientList: [],

    async loadPatient(patientId, folderId) {
      console.log("loadPatient searchFolder.js");

      if (!patientId) {
        this.patient = {};
        this.folder = {};
        this.folderPatientList = [];
        return;
      }

      this.patient = await getPatient(patientId);

      // Sort by descending order id
      const results = await getFoldersByPatient(patientId);
      this.folderPatientList = results.filter((folder) => folder.archivedAt == null).sort((a, b) => b.id - a.id);

      if (folderId) {
        this.onSelectFolder(folderId, false);
      }
    },

    loadFolder(folderId) {
      if (!folderId) {
        this.folder = {};
        return;
      }

      this.onSelectFolder(folderId, false);
    },

    async onArchivedFolder(folderId) {
      if (!folderId || (this.folder.length > 0 && folderId !== this.folder.id)) {
        return;
      }
      // Disabled all navigation tab
      customDispatch("lock-tab", { lock: true });

      // Reset all tab with no value
      customDispatch("select-folder", { patientId: this.patient.id, folderId: null });

      // Sort by descending order id
      const results = await getFoldersByPatient(this.patient.id);
      this.folderPatientList = results.filter((folder) => folder.archivedAt == null).sort((a, b) => b.id - a.id);
    },

    getPatientFullname() {
      return this.patient.gender + " " + this.patient.lastname + " " + this.patient.firstname;
    },

    getFolderName() {
      return this.folder.name;
    },

    onSelectFolder(id, withReload = true) {
      if (!id) return;
      const folder = this.folderPatientList.find((f) => f.id == id);
      if (!folder) return;

      this.folder = folder;

      // Enable all navigation tab
      customDispatch("lock-tab", { lock: false });

      if (withReload) {
        // Reload all the tabs with the new values
        customDispatch("select-folder", { patientId: this.patient.id, folderId: folder.id });
      }
    },

    async onAddFolder() {
      if (!this.patient) return;

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
        patient_id: this.patient.id,
        pdf_parameter_id: pdfParameter.id,
        name: folderName,
      });

      this.folder = await getFolder(folder.id);

      // Reload all the tabs with the new values
      customDispatch("select-folder", { patientId: this.patient.id, folderId: folder.id });
    },
  };
}
