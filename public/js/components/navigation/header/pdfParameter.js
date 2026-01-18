function pdfParameter() {
  return {
    globalPdfParameter: {},
    isEdit: false,

    async init() {
      this.globalPdfParameter = await getGlobalPdfParameter();
    },

    async onSavePdfParameter() {
      this.globalPdfParameter = await updatePdfParameter(this.globalPdfParameter);
      this.isEdit = false;
    },
  };
}
