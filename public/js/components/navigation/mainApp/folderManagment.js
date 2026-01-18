function folderManagment() {
  return {
    async load(patient, ignore = false) {
      if (ignore) return;

      if (!patient) {
        this.patient = null;
        return;
      }

      this.patient = patient;

      const results = await getFoldersByPatient(this.patient.id);
      this.folders = results.sort((a, b) => b.id - a.id);
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

    async onArchived(folder) {
      result = this.folders.filter((f) => f.id !== folder.id).filter((f) => f.archivedAt == null);

      if (!result.length) {
        customDispatch("notify", {
          message: "Le patient doit avoir au minimum un dossier actif",
          type: "alert-warning",
        });
        return;
      }

      const updatedFolder = await archivedFolder(folder);

      this.folders = this.folders.map((f) => (f.id === updatedFolder.id ? updatedFolder : f));
      this.activeFolders = this.activeFolders
        .filter((folder) => folder.id !== updatedFolder.id)
        .sort((a, b) => b.id - a.id);
    },

    async onUnarchived(folder) {
      const updatedFolder = await unarchivedFolder(folder);

      this.folders = this.folders.map((f) => (f.id === updatedFolder.id ? updatedFolder : f));

      this.activeFolders.push(updatedFolder);
      this.activeFolders = this.activeFolders.sort((a, b) => b.id - a.id);
    },
  };
}
