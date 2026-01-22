function library() {
  return {
    libraries: [],
    librariesNames: {
      localisation: "Localisation",
      observation: "Observation",
      equipmentList: "Equipement",
      equipmentDetail: "Détail de l'équipement",
    },

    async initData(data) {
      this.libraries = [
        {
          name: "localisation",
          suggestions: data.filter((l) => l.name == "localisation"),
        },
        {
          name: "observation",
          suggestions: data.filter((l) => l.name == "observation"),
        },
        {
          name: "equipmentList",
          suggestions: data.filter((l) => l.name == "equipmentList"),
        },
        {
          name: "equipmentDetail",
          suggestions: data.filter((l) => l.name == "equipmentDetail"),
        },
      ];
    },

    async onAddSuggestion(suggestionName, input) {
      const inputValue = input.value.trim();
      if (inputValue == "") return;

      const lib = this.libraries.find((lib) => lib.name === suggestionName);
      const suggestion = lib.suggestions.find((sug) => sug.value.trim() === inputValue.trim());
      if (suggestion) return;

      try {
        const newSuggestion = await createSuggestion({
          name: suggestionName,
          value: inputValue,
        });

        input.value = "";
        lib.suggestions.push(newSuggestion);

        customDispatch("add-suggestion", { suggestion: newSuggestion });
      } catch (err) {
        console.error("Erreur patient →", err);
        customDispatch("notify", {
          message: "Une erreur est survenue. Veuillez rafraichir la page",
          type: "alert-danger",
        });
      }
    },

    async onRemoveSuggestion(suggestionName, id) {
      const lib = this.libraries.find((lib) => lib.name === suggestionName);
      if (lib.suggestions.length == 1) {
        customDispatch("notify", {
          message: "La liste doit contenir au minimum une suggestion",
          type: "alert-warning",
        });
        return;
      }

      try {
        const suggestion = lib.suggestions.find((s) => s.id === id);
        await deleteSuggestion(id);
        lib.suggestions = lib.suggestions.filter((s) => s.id !== id);
        customDispatch("remove-suggestion", { suggestion });
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
