function anamnesis() {
  return {
    getFormatedDate(dateString) {
      if (!dateString) return "";
      const d = new Date(dateString);

      const datePart = new Intl.DateTimeFormat("fr-FR", {
        day: "2-digit",
        month: "2-digit",
        year: "numeric",
      }).format(d);

      const pad = (n) => n.toString().padStart(2, "0");
      const timePart = `${pad(d.getHours())}h${pad(d.getMinutes())}`;

      return `${datePart} à ${timePart}`;
    },

    async onSave() {
      const now = new Date();
      const isNewPatient = !this.patient.id;
      this.patient.updatedAt = isNewPatient ? null : now.toISOString();

      try {
        if (isNewPatient) {
          await this.createPatient();
          this.displayTab = true;
          this.lockTab = false;
          customDispatch("notify", { message: "Patient ajouté", type: "alert-success" });
        } else {
          this.patient = await updatePatient(this.patient.id, { ...this.patient });
          customDispatch("notify", { message: "Patient modifié", type: "alert-warning" });
        }
      } catch (err) {
        console.error("Erreur patient →", err);
        customDispatch("notify", {
          message: isNewPatient ? "Erreur lors de l'ajout du patient" : "Erreur lors de la modification du patient",
          type: "alert-danger",
        });
      }
    },
  };
}
