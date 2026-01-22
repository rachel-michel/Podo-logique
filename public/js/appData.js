function appData() {
  return {
    // Global data
    patients: [],
    globalPdfParameter: {},
    libraries: [],
    prescribers: [],
    // Patient data
    displayTab: false,
    lockTab: true,
    patient: {
      id: null,
      gender: "Mme",
      lastname: "",
      firstname: "",
      dateOfBirth: "",
      phoneNumber: "",
      address: "",
      folderPrefix: "Consultation du",
      folderPrefixFormat: "prefixDate",
      weight: 0,
      height: 0,
      shoeSize: 0,
      job: "",
      physicalActivity: "",
      pathology: "",
      medicalHistory: "",
      notices: "",
      createdAt: null,
      updatedAt: null,
    },
    folders: [],
    folder: {},
    activeFolders: [],
    pdfParameter: {},
    examinations: [],
    reportExamination: [],
    equipmentPlan: [],
    templateTabs: [
      {
        name: "visualExamination",
        rows: [],
        suggestion: {
          first: "localisation",
          second: "observation",
        },
        column: {
          first: "Localisation",
          second: "Observation",
        },
      },
      {
        name: "palpatoryExamination",
        rows: [],
        suggestion: {
          first: "localisation",
          second: "observation",
        },
        column: {
          first: "Localisation",
          second: "Observation",
        },
      },
      {
        name: "podoscopicExamination",
        rows: [],
        suggestion: {
          first: "localisation",
          second: "observation",
        },
        column: {
          first: "Localisation",
          second: "Observation",
        },
      },
      {
        name: "walkStudy",
        rows: [],
        suggestion: {
          first: "localisation",
          second: "observation",
        },
        column: {
          first: "Localisation",
          second: "Observation",
        },
      },
      {
        name: "equipmentPlan",
        rows: [],
        suggestion: {
          first: "equipmentList",
          second: "equipmentDetail",
        },
        column: {
          first: "Appareillage",
          second: "Details",
        },
      },
    ],

    async init() {
      this.patients = await getAllPatient();

      this.prescribers = await getAllPrescriber();
      customDispatch("init-prescribers", { prescribers: this.prescribers });

      this.libraries = await getAllSuggestion();
      customDispatch("init-libraries", { libraries: this.libraries });

      this.globalPdfParameter = await getGlobalPdfParameter();
      customDispatch("init-global-pdf-parameter", { globalPdfParameter: this.globalPdfParameter });
    },

    updateGlobalPdfParameter(globalPdfParameter) {
      this.globalPdfParameter = globalPdfParameter;
    },

    async selectPatient(patient, pdfParameter, folder) {
      if (!patient || !("id" in patient) || patient.id == null) {
        customDispatch("notify", {
          message: "Une erreur est survenue lors de la selection du patient, merci de rafraichir la page",
          type: "alert-danger",
        });
        return;
      }

      this.resetMainAppData();

      this.patient = patient;

      this.folders = await getFoldersByPatient(patient.id);
      this.folder = folder || this.folders.filter((folder) => folder.archivedAt == null).sort((a, b) => b.id - a.id)[0];
      this.activeFolders = this.folders.filter((folder) => folder.archivedAt == null).sort((a, b) => b.id - a.id);

      this.pdfParameter = pdfParameter || (await getPdfParameterByFolder(this.folder.id));

      this.examinations = await getExaminationByFolder(this.folder.id);
      this.equipmentPlan = this.examinations.filter((e) => e.name == "equipmentPlan").sort((a, b) => a.id - b.id);
      this.formatExaminations();
    },

    async selectFolder(folder) {
      this.folder = folder;

      this.pdfParameter = await getPdfParameterByFolder(folder.id);

      this.examinations = await getExaminationByFolder(folder.id);
      this.equipmentPlan = this.examinations.filter((e) => e.name == "equipmentPlan").sort((a, b) => a.id - b.id);
      this.formatExaminations();
    },

    async addFolder(folder, pdfParameter) {
      this.folders = [...this.folders, folder];
      this.activeFolders = [...this.activeFolders, folder].sort((a, b) => b.id - a.id);

      this.pdfParameter = pdfParameter;

      this.examinations = await getExaminationByFolder(folder.id);
      this.equipmentPlan = this.examinations.filter((e) => e.name == "equipmentPlan").sort((a, b) => a.id - b.id);
      this.formatExaminations();
    },

    formatExaminations() {
      // Examinations tabs (visual, palpatory, podoscopic, walk, equipment plan)
      let examinationList = {
        visualExamination: this.examinations
          .filter((e) => e.name == "visualExamination")
          .map((a) => ({
            id: a.id,
            editing: false,
            _refs: {},
            localisationInput: a.localisation,
            observationInput: a.observation,
          }))
          .sort((a, b) => a.id - b.id),
        palpatoryExamination: this.examinations
          .filter((e) => e.name == "palpatoryExamination")
          .map((a) => ({
            id: a.id,
            editing: false,
            _refs: {},
            localisationInput: a.localisation,
            observationInput: a.observation,
          }))
          .sort((a, b) => a.id - b.id),
        podoscopicExamination: this.examinations
          .filter((e) => e.name == "podoscopicExamination")
          .map((a) => ({
            id: a.id,
            editing: false,
            _refs: {},
            localisationInput: a.localisation,
            observationInput: a.observation,
          }))
          .sort((a, b) => a.id - b.id),
        walkStudy: this.examinations
          .filter((e) => e.name == "walkStudy")
          .map((a) => ({
            id: a.id,
            editing: false,
            _refs: {},
            localisationInput: a.localisation,
            observationInput: a.observation,
          }))
          .sort((a, b) => a.id - b.id),
        equipmentPlan: this.examinations
          .filter((e) => e.name == "equipmentPlan")
          .map((a) => ({
            id: a.id,
            editing: false,
            _refs: {},
            localisationInput: a.localisation,
            observationInput: a.observation,
          }))
          .sort((a, b) => a.id - b.id),
      };

      for (let template of this.templateTabs) {
        template.rows = examinationList[template.name];
        template.rows.push({
          editing: false,
          _refs: {},
          localisationInput: "",
          observationInput: "",
        });
      }

      // Report Tabs
      this.reportExamination = [
        {
          show: this.pdfParameter.showTabA,
          name: "Examen visuel",
          rows: this.examinations.filter((e) => e.name == "visualExamination").sort((a, b) => a.id - b.id),
        },
        {
          show: this.pdfParameter.showTabB,
          name: "Examen palpatoire",
          rows: this.examinations.filter((e) => e.name == "palpatoryExamination").sort((a, b) => a.id - b.id),
        },
        {
          show: this.pdfParameter.showTabC,
          name: "Examen podoscopique",
          rows: this.examinations.filter((e) => e.name == "podoscopicExamination").sort((a, b) => a.id - b.id),
        },
        {
          show: this.pdfParameter.showTabD,
          name: "Etude de la marche",
          rows: this.examinations.filter((e) => e.name == "walkStudy").sort((a, b) => a.id - b.id),
        },
      ];
    },

    resetMainAppData() {
      this.displayTab = false;
      this.lockTab = true;

      this.patient = {
        id: null,
        gender: "Mme",
        lastname: "",
        firstname: "",
        dateOfBirth: "",
        phoneNumber: "",
        address: "",
        folderPrefix: "Consultation du",
        folderPrefixFormat: "prefixDate",
        weight: 0,
        height: 0,
        shoeSize: 0,
        job: "",
        physicalActivity: "",
        pathology: "",
        medicalHistory: "",
        notices: "",
        createdAt: null,
        updatedAt: null,
      };

      this.folders = [];
      this.folder = {};
      this.activeFolders = [];

      this.pdfParameter = {};

      this.examinations = [];
      this.reportExamination = [];
      this.equipmentPlan = [];

      this.templateTabs = [
        {
          name: "visualExamination",
          rows: [],
          suggestion: {
            first: "localisation",
            second: "observation",
          },
          column: {
            first: "Localisation",
            second: "Observation",
          },
        },
        {
          name: "palpatoryExamination",
          rows: [],
          suggestion: {
            first: "localisation",
            second: "observation",
          },
          column: {
            first: "Localisation",
            second: "Observation",
          },
        },
        {
          name: "podoscopicExamination",
          rows: [],
          suggestion: {
            first: "localisation",
            second: "observation",
          },
          column: {
            first: "Localisation",
            second: "Observation",
          },
        },
        {
          name: "walkStudy",
          rows: [],
          suggestion: {
            first: "localisation",
            second: "observation",
          },
          column: {
            first: "Localisation",
            second: "Observation",
          },
        },
        {
          name: "equipmentPlan",
          rows: [],
          suggestion: {
            first: "equipmentList",
            second: "equipmentDetail",
          },
          column: {
            first: "Appareillage",
            second: "Details",
          },
        },
      ];
    },
  };
}
