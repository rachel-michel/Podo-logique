function searchPatient() {
  return {
    query: "",
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

      // Init values with selected patient
      this.selectPatient(patient);

      // Force return to the anamnesis tab
      var tabTrigger = document.querySelector("#anamnesisTab");
      bootstrap.Tab.getOrCreateInstance(tabTrigger).show();

      // Show navigation tab but disabled
      this.displayTab = true;
      this.lockTab = false;
    },

    resetSearchBar() {
      this.query = "";
      this.patientList = [];
    },
  };
}
