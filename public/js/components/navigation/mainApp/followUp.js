function followUp() {
  return {
    newNote: "",
    inputEditNote: {},

    getTimeSinceFirstNote(note) {
      const firstNote = this.followUpNote.at(-1);

      if (firstNote.id === note.id) return "Première note";
      if (!firstNote || !firstNote.createdAt) return "";

      const now = new Date();
      const createdAt = new Date(firstNote.createdAt);

      const diffMs = now - createdAt;

      const totalMinutes = Math.floor(diffMs / (1000 * 60));
      const totalHours = Math.floor(totalMinutes / 60);
      const totalDays = Math.floor(totalHours / 24);

      // Moins d'une heure
      if (totalMinutes < 60) {
        return `${totalMinutes} minute${totalMinutes > 1 ? "s" : ""} depuis la première note`;
      }

      // Moins d'un jour → heures
      if (totalDays < 1) {
        return `${totalHours} heure${totalHours > 1 ? "s" : ""} depuis la première note`;
      }

      // Moins d'une semaine → jours
      if (totalDays < 7) {
        return `${totalDays} jour${totalDays > 1 ? "s" : ""} depuis la première note`;
      }

      // Moins d'un mois → semaines + jours
      if (totalDays < 30) {
        const weeks = Math.floor(totalDays / 7);
        const days = totalDays % 7;

        if (days === 0) {
          return `${weeks} semaine${weeks > 1 ? "s" : ""} depuis la première note`;
        }

        return `${weeks} semaine${weeks > 1 ? "s" : ""} et ${days} jour${days > 1 ? "s" : ""} depuis la première note`;
      }

      // Mois + semaines
      const months = Math.floor(totalDays / 30);
      const remainingDays = totalDays % 30;
      const weeks = Math.floor(remainingDays / 7);

      if (weeks === 0) {
        return `${months} mois`;
      }

      return `${months} mois et ${weeks} semaine${weeks > 1 ? "s" : ""} depuis la première note`;
    },

    onClickNote(note) {
      this.inputEditNote = {
        id: note.id,
        text: note.text,
      };
    },

    async onAddNote() {
      try {
        const note = await createNote({
          folder_id: this.folder.id,
          text: this.newNote.trim(),
        });

        this.newNote = "";

        this.followUpNote.unshift(note);
      } catch (err) {
        console.error("Erreur →", err);
        customDispatch("notify", {
          message: "Une erreur est survenue. Veuillez rafraichir la page",
          type: "alert-danger",
        });
      }
    },

    async onEditNote(note) {
      try {
        const updatedNote = await updateNote(note);
        this.followUpNote = this.followUpNote.map((f) => (f.id === updatedNote.id ? updatedNote : f));

        this.inputEditNote = {
          id: null,
          text: "",
        };
      } catch (err) {
        console.error("Erreur →", err);
        customDispatch("notify", {
          message: "Une erreur est survenue. Veuillez rafraichir la page",
          type: "alert-danger",
        });
      }
    },

    async onRemoveNote(note) {
      try {
        await deleteNote(note.id);
        this.followUpNote = !this.followUpNote.length ? [] : this.followUpNote.filter((n) => n.id !== note.id);
      } catch (err) {
        console.error("Erreur →", err);
        customDispatch("notify", {
          message: "Une erreur est survenue. Veuillez rafraichir la page",
          type: "alert-danger",
        });
      }
    },
  };
}
