function pdfParameter() {
  return {
    globalPdfParameter: {},
    isEdit: false,

    async initData(data) {
      this.globalPdfParameter = data;
    },

    async onEditGlobalPdfParameter() {
      try {
        this.globalPdfParameter = await updatePdfParameter(this.globalPdfParameter);
        this.isEdit = false;

        customDispatch("update-global-pdf-parameter", { globalPdfParameter: this.globalPdfParameter });
      } catch (err) {
        console.error("Erreur patient →", err);
        customDispatch("notify", {
          message: "Une erreur est survenue. Veuillez rafraichir la page",
          type: "alert-danger",
        });
      }
    },
  };
}
