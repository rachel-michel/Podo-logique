function library() {
  return {
    libraries: [],
    librariesNames: {
      localisation: "Localisation",
      observation: "Observation",
      equipmentList: "Equipement",
      equipmentDetail: "Détail de l'équipement",
    },
    keepSuggestion: [
      "Orthèses plantaires",
      "Anneau neutre",
      "Anneau pronateur",
      "Anneau supinateur",
      "Arciforme",
      "ARCM - appui retro capital médian",
      "BAC - barre antéro capitale totale",
      "Décharge de l'hallux",
      "Décharge des orteils 1-2",
      "Décharge du 2eme orteil",
      "Décharge des orteils 2-3",
      "Décharge des orteils 2-3-4",
      "Décharge du 3eme orteil",
      "Décharge des orteils 3-4",
      "Décharge des orteils 3-4-5",
      "Décharge du 4eme orteil",
      "Décharge des orteils 4-5",
      "Décharge du 5eme orteil",
      "Barre sous diaphysaire",
      "BRC - Barre rétro capital",
      "CPP - coin pronateur postérieur",
      "CSP - coin supinateur postérieur",
      "EPA - élément pronateur antérieur",
      "EPP - élément pronateur postérieur",
      "EPT - élément pronateur total",
      "ESP - élément supinateur postérieur",
      "Hémicoupole de décharge de l'hallux",
      "SAC - sous antéro capital court",
      "SAC - sous antéro capital long",
      "Sous cuboïdien",
      "Sous naviculaire",
      "Talonnette",
      "Voûte",
    ],

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

    isCanDelete(suggestion) {
      if (this.keepSuggestion.includes(suggestion)) return false;
      return true;
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
