function FollowUpNote() {
  return {
    newNote: "",

    async onAddNote() {
      console.log(this.newNote);

      try {
        const note = await createNote({
          folder_id: this.folder.id,
          text: this.newNote.trim(),
        });

        this.newNote = "";

        this.followUpNote.push(note);
      } catch (err) {
        console.error("Erreur →", err);
        customDispatch("notify", {
          message: "Une erreur est survenue. Veuillez rafraichir la page",
          type: "alert-danger",
        });
      }
    },

    async onEditNote(note) {
      console.log(note);

      try {
        await updateNote(note);
      } catch (err) {
        console.error("Erreur →", err);
        customDispatch("notify", {
          message: "Une erreur est survenue. Veuillez rafraichir la page",
          type: "alert-danger",
        });
      }
    },

    async onRemoveNote(note) {
      console.log(note);

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
