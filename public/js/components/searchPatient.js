function searchPatient() {
  return {
    query: "",
    selectedPatient: {},
    patientList: [],

    async onSearchPatients() {
      if (this.query.trim() === "") {
        this.resetSearchBar();
        return;
      }

      const all = await getAllPatient();

      const search = this.query.toLowerCase().trim();
      const terms = search.split(/\s+/);

      this.patientList = all
        .filter((p) => {
          const fullName = (p.lastname + " " + p.firstname).toLowerCase();
          return terms.every((term) => fullName.includes(term));
        })
        .slice(0, 10);
    },

    async onSelectPatient(id) {
      id = parseInt(id);
      if (!id) return;
      const patient = this.patientList.find((p) => p.id == id);
      if (!patient) return;
      this.selectedPatient = patient;

      // Sort by descending order id
      const results = await getFoldersByPatient(id);
      const lastPatientFolder = results.filter((folder) => folder.archivedAt == null).sort((a, b) => b.id - a.id)[0];

      // Force return to the anamnesis tab
      var tabTrigger = document.querySelector("#anamnesisTab");
      bootstrap.Tab.getOrCreateInstance(tabTrigger).show();

      // Show navigation tab but disabled
      customDispatch("display-tab", { display: true });
      customDispatch("lock-tab", { lock: true });

      // Reload all tab value with patient selected
      customDispatch("select-patient", { patient, folder: lastPatientFolder });
      customDispatch("select-folder", { patient, folder: lastPatientFolder, ignore: true });
    },

    resetSearchBar() {
      this.query = "";
      this.selectedPatient = {};
      this.patientList = [];
    },
  };
}
