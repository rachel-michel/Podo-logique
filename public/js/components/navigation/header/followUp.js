function followUp() {
  return {
    patientList: [],
    range: "12+",

    async initData(data) {
      this.patientList = data;
    },

    getPatientsByDeliveryRange() {
      const now = new Date();

      const ranges = {
        "0-3": { min: 0, max: 3 },
        "3-6": { min: 3, max: 6 },
        "6-12": { min: 6, max: 12 },
        "12+": { min: 12, max: Infinity },
      };

      const selected = ranges[this.range];
      if (!selected) {
        return [];
      }

      return this.patientList.filter((patient) => {
        if (!patient.lastDeliveryAt) return false;

        const last = new Date(patient.lastDeliveryAt);
        const diffMonths = (now.getFullYear() - last.getFullYear()) * 12 + (now.getMonth() - last.getMonth());

        return diffMonths >= selected.min && diffMonths < selected.max;
      });
    },

    onSelectPatient(patient) {
      var tabTrigger = document.querySelector("#homeTab");
      bootstrap.Tab.getOrCreateInstance(tabTrigger).show();

      customDispatch("select-patient", { patient });
    },
  };
}
