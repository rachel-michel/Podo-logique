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

    async initData(data) {
      this.prescriberList = data.sort((a, b) => a.fullname.localeCompare(b.fullname, "fr", { sensitivity: "base" }));
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

    async onAddPrescriber() {
      if (this.inputPrescriber.fullname.trim() == "") {
        customDispatch("notify", {
          message: "Vous devez renseigner au minimum le nom du prescripteur",
          type: "alert-warning",
        });
        return;
      }

      const existingPrescriber = this.prescriberList.find((p) => p.fullname === this.inputPrescriber.fullname.trim());
      if (existingPrescriber) {
        customDispatch("notify", {
          message: "Un prescripteur existe déjà avec le nom : " + this.inputPrescriber.fullname.trim(),
          type: "alert-warning",
        });
        return;
      }

      try {
        const prescriber = await createPrescriber({
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

        this.prescriberList.push(prescriber);
        this.prescriberList.sort((a, b) => a.fullname.localeCompare(b.fullname, "fr", { sensitivity: "base" }));

        customDispatch("add-prescriber", { prescriber });
      } catch (err) {
        console.error("Erreur patient →", err);
        customDispatch("notify", {
          message: "Une erreur est survenue. Veuillez rafraichir la page",
          type: "alert-danger",
        });
      }
    },

    async onEditPrescriber() {
      if (this.inputEditPrescriber.fullname == "") {
        customDispatch("notify", {
          message: "Vous devez renseigner au minimum le nom du prescripteur",
          type: "alert-warning",
        });
        return;
      }

      const existingPrescriber = this.prescriberList.find(
        (p) => p.id !== this.inputEditPrescriber.id && p.fullname === this.inputEditPrescriber.fullname.trim(),
      );
      if (existingPrescriber) {
        customDispatch("notify", {
          message:
            "Impossible de renommer ce prescripteur, un autre existe déjà avec ce nom : " +
            this.inputEditPrescriber.fullname.trim(),
          type: "alert-warning",
        });
        return;
      }

      try {
        const prescriber = await updatePrescriber({
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

        this.prescriberList = this.prescriberList.map((p) => (p.id === prescriber.id ? prescriber : p));
        customDispatch("update-prescriber", { prescriber });
      } catch (err) {
        console.error("Erreur patient →", err);
        customDispatch("notify", {
          message: "Une erreur est survenue. Veuillez rafraichir la page",
          type: "alert-danger",
        });
      }
    },

    async onRemovePrescriber(id) {
      const prescriber = this.prescriberList.find((p) => p.id === id);

      try {
        await deletePrescriber(id);
        this.prescriberList = !this.prescriberList.length ? [] : this.prescriberList.filter((p) => p.id !== id);
        customDispatch("remove-prescriber", { prescriber });
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
