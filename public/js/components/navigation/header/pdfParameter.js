function pdfParameter() {
  return {
    pdfParameter: {},
    isEdit: false,

    async init() {
      this.pdfParameter = await getGlobalPdfParameter();
    },

    async onSavePdfParameter() {
      await updatePdfParameter(this.pdfParameter);
      this.isEdit = false;
    },
  };
}
