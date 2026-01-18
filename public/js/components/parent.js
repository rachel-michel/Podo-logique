function parent() {
  return {
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
    activeFolders: [],
    folder: {},
    globalPdfParameter: {},
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

    async createPatient() {
      const patient = await createPatient({ ...this.patient });

      // Create new pdf parameters for the folder
      const pdfParameter = await createPdfParameter({
        office: this.globalPdfParameter.office,
        prescriberFullname: "",
        prescriberAddress: "",
        prescriberMail: "",
        prescriberPhoneNumber: "",
        subject: this.globalPdfParameter.subject,
        notes: "",
        showTabA: this.globalPdfParameter.showTabA,
        showTabB: this.globalPdfParameter.showTabB,
        showTabC: this.globalPdfParameter.showTabC,
        showTabD: this.globalPdfParameter.showTabD,
      });

      // Create new folder
      const folderName = getFolderName(patient.folderPrefixFormat, patient.folderPrefix);
      await createFolder({
        patient_id: patient.id,
        pdf_parameter_id: pdfParameter.id,
        name: folderName,
      });

      this.selectPatient(patient);
    },

    async selectPatient(patient) {
      this.patient = patient;
      this.folders = await getFoldersByPatient(patient.id);
      this.activeFolders = this.folders.filter((folder) => folder.archivedAt == null).sort((a, b) => b.id - a.id);
      this.folder = this.folders.filter((folder) => folder.archivedAt == null).sort((a, b) => b.id - a.id)[0];
      this.globalPdfParameter = await getGlobalPdfParameter();
      this.pdfParameter = await getPdfParameterByFolder(this.folder.id);
      this.examinations = await getExaminationByFolder(this.folder.id);
      this.equipmentPlan = this.examinations.filter((e) => e.name == "equipmentPlan").sort((a, b) => a.id - b.id);
      this.loadExamination();
    },

    async selectFolder(folder) {
      this.folder = folder;
      this.pdfParameter = await getPdfParameterByFolder(folder.id);
      this.examinations = await getExaminationByFolder(folder.id);
      this.equipmentPlan = this.examinations.filter((e) => e.name == "equipmentPlan").sort((a, b) => a.id - b.id);
      this.loadExamination();
    },

    async createFolder() {
      // Create new pdf parameters for the folder
      this.pdfParameter = await createPdfParameter({
        office: this.globalPdfParameter.office,
        prescriberFullname: "",
        prescriberAddress: "",
        prescriberMail: "",
        prescriberPhoneNumber: "",
        subject: this.globalPdfParameter.subject,
        notes: "",
        showTabA: this.globalPdfParameter.showTabA,
        showTabB: this.globalPdfParameter.showTabB,
        showTabC: this.globalPdfParameter.showTabC,
        showTabD: this.globalPdfParameter.showTabD,
      });

      // Create new folder
      const folderName = getFolderName(this.patient.folderPrefixFormat, this.patient.folderPrefix);
      const folder = await createFolder({
        patient_id: this.patient.id,
        pdf_parameter_id: this.pdfParameter.id,
        name: folderName,
      });

      // update data
      this.folders = [...this.folders, folder];
      this.activeFolders = [...this.activeFolders, folder].sort((a, b) => b.id - a.id);
      this.examinations = await getExaminationByFolder(folder.id);
      this.equipmentPlan = this.examinations.filter((e) => e.name == "equipmentPlan").sort((a, b) => a.id - b.id);
      this.loadExamination();
    },

    loadExamination() {
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

    resetPatient() {
      this.displayTab = false;
      this.lockTab = true;
      this.folder = {};
      this.folders = [];
      this.globalPdfParameter = {};
      this.pdfParameter = {};
      this.examinations = [];
      this.equipmentPlan = [];
      this.activeFolders = [];

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
