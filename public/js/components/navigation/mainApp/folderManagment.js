function folderManagment() {
  return {
    patientId: null,
    folderList: [],

    async loadPatient(patientId) {
      console.log("loadPatient folderManagment.js");

      if (!patientId) {
        return;
      }

      this.patientId = patientId;

      // Sort by descending order id
      const results = await getFoldersByPatient(patientId);
      this.folderList = results.sort((a, b) => b.id - a.id);
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
      result = this.folderList.filter((f) => f.id !== folder.id).filter((f) => f.archivedAt == null);

      if (!result.length) {
        customDispatch("notify", {
          message: "Le patient doit avoir au minimum un dossier actif",
          type: "alert-warning",
        });
        return;
      }

      await archivedFolder(folder);
      customDispatch("archived-folder", { folderId: folder.id });
      this.loadPatient(folder.patient_id);
    },

    async onUnarchived(folder) {
      await unarchivedFolder(folder);
      customDispatch("unarchived-folder", { patientId: this.patientId });
      this.loadPatient(folder.patient_id);
    },
  };
}
