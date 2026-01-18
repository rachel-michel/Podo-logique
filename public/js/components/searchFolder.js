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

      this.createFolder();
    },
  };
}
