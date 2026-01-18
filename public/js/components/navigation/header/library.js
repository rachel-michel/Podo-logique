function library() {
  return {
    libraries: [],
    librariesNames: {
      localisation: "Localisation",
      observation: "Observation",
      equipmentList: "Equipement",
      equipmentDetail: "Détail de l'équipement",
    },

    async init() {
      await this.loadLibraries();
    },

    async loadLibraries() {
      const result = await getAllSuggestion();
      this.libraries = [
        {
          name: "localisation",
          suggestions: result.filter((l) => l.name == "localisation"),
        },
        {
          name: "observation",
          suggestions: result.filter((l) => l.name == "observation"),
        },
        {
          name: "equipmentList",
          suggestions: result.filter((l) => l.name == "equipmentList"),
        },
        {
          name: "equipmentDetail",
          suggestions: result.filter((l) => l.name == "equipmentDetail"),
        },
      ];
    },

    async addSuggestion(suggestionName, input) {
      const inputValue = input.value.trim();
      if (inputValue == "") return;

      const lib = this.libraries.find((lib) => lib.name === suggestionName);
      const suggestion = lib.suggestions.find((sug) => sug.value.trim() === inputValue.trim());

      if (suggestion) return;

      await createSuggestion({
        name: suggestionName,
        value: inputValue,
      });

      input.value = "";
      customDispatch("update-suggestion");
      this.loadLibraries();
    },

    async removeSuggestion(suggestionName, id) {
      const lib = this.libraries.find((lib) => lib.name === suggestionName);

      if (lib.suggestions.length == 1) {
        customDispatch("notify", {
          message: "La liste doit contenir au minimum une suggestion",
          type: "alert-warning",
        });
        return;
      }

      await deleteSuggestion(id);
      customDispatch("update-suggestion");
      this.loadLibraries();
    },
  };
}
