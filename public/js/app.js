// ENABLE TOOLTIP BOOTSTRAP
const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
const tooltipList = [...tooltipTriggerList].map((tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl));

// CUSTOM DISPATCH EVENT
function customDispatch(name, details) {
  document.dispatchEvent(
    new CustomEvent(name, {
      detail: details,
    }),
  );
}

// ALERT SYSTEM
function alertHandler() {
  return {
    alert: { visible: false, message: "", type: "alert-info" },

    init() {
      document.addEventListener("notify", (e) => {
        this.show(e.detail.message, e.detail.type);
      });
    },

    show(message, type = "alert-info") {
      this.alert.message = message;
      this.alert.type = type;
      this.alert.visible = true;
      setTimeout(() => (this.alert.visible = false), 5000);
    },
  };
}
