function reset() {
  return {
    isSelectedPatient: false,

    loadPatient(patientId) {
      this.isSelectedPatient = !!patientId;
    },

    onClose() {
      // Force return to the anamnesis tab
      var tabTrigger = document.querySelector("#anamnesisTab");
      bootstrap.Tab.getOrCreateInstance(tabTrigger).show();

      // Hide all navigation tab
      customDispatch("display-tab", { display: false });

      // Disabled all navigation tab
      customDispatch("lock-tab", { lock: true });

      // Reset all tab with no value
      customDispatch("close-patient");
    },
  };
}
