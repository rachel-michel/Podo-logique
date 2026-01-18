function prescriber() {
  return {
    prescriberList: [],
    inputPrescriber: {
      fullname: "",
      address: "",
      mail: "",
      phoneNumber: "",
    },
    inputEditPrescriber: {
      id: null,
      fullname: "",
      address: "",
      mail: "",
      phoneNumber: "",
    },
    editingField: null,

    async init() {
      this.loadPrescriberList();
    },

    async loadPrescriberList() {
      const prescriberList = await getAllPrescriber();
      this.prescriberList = prescriberList.sort((a, b) =>
        a.fullname.localeCompare(b.fullname, "fr", { sensitivity: "base" }),
      );
    },

    onClickPrescriber(prescriber, field) {
      this.editingField = field;
      this.inputEditPrescriber = {
        id: prescriber.id,
        fullname: prescriber.fullname,
        address: prescriber.address,
        mail: prescriber.mail,
        phoneNumber: prescriber.phoneNumber,
      };
    },

    async addPrescriber() {
      if (this.inputPrescriber.fullname.trim() == "") {
        customDispatch("notify", {
          message: "Vous devez renseigner au minimum le nom du prescripteur",
          type: "alert-warning",
        });
        return;
      }

      await createPrescriber({
        fullname: this.inputPrescriber.fullname.trim(),
        address: this.inputPrescriber.address.trim(),
        mail: this.inputPrescriber.mail.trim(),
        phoneNumber: this.inputPrescriber.phoneNumber.trim(),
      });

      this.inputPrescriber = {
        fullname: "",
        address: "",
        mail: "",
        phoneNumber: "",
      };

      this.loadPrescriberList();
      customDispatch("update-prescriber");
    },

    async editPrescriber() {
      if (this.inputEditPrescriber.fullname == "") {
        customDispatch("notify", {
          message: "Vous devez renseigner au minimum le nom du prescripteur",
          type: "alert-warning",
        });
        return;
      }

      await updatePrescriber({
        id: this.inputEditPrescriber.id,
        fullname: this.inputEditPrescriber.fullname.trim(),
        address: this.inputEditPrescriber.address.trim(),
        mail: this.inputEditPrescriber.mail.trim(),
        phoneNumber: this.inputEditPrescriber.phoneNumber.trim(),
      });

      this.editingField = null;
      this.inputEditPrescriber = {
        id: null,
        fullname: "",
        address: "",
        mail: "",
        phoneNumber: "",
      };

      await this.loadPrescriberList();
      customDispatch("update-prescriber");
    },

    async removePrescriber(id) {
      await deletePrescriber(id);

      this.loadPrescriberList();
      customDispatch("update-prescriber");
    },
  };
}
