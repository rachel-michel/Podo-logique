function searchPatient() {
  return {
    query: "",
    patientList: [],

    async onSearchPatients() {
      if (this.query.trim() === "") {
        this.resetSearchBar();
        return;
      }

      const search = this.query.toLowerCase().trim();
      const terms = search.split(/\s+/);

      this.patientList = this.patients
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
    },

    resetSearchBar() {
      this.query = "";
      this.patientList = [];
    },
  };
}
