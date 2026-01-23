function pdfParameter() {
  return {
    globalPdfParameter: {},

    async initData(data) {
      this.globalPdfParameter = data;
    },

    async onEditGlobalPdfParameter() {
      try {
        this.globalPdfParameter = await updatePdfParameter(this.globalPdfParameter);
        customDispatch("notify", { message: "Les modifications ont bien été prises en compte", type: "alert-success" });

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
