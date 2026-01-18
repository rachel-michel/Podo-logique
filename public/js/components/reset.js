function reset() {
  return {
    onClose() {
      // Force return to the anamnesis tab
      var tabTrigger = document.querySelector("#anamnesisTab");
      bootstrap.Tab.getOrCreateInstance(tabTrigger).show();

      // Hide all navigation tab
      this.displayTab = false;

      // Disabled all navigation tab
      this.lockTab = true;

      // Reset all tab with no value
      this.resetPatient();

      customDispatch("close-patient");
    },
  };
}
