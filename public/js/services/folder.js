function getFolderName(prefixFormat, prefixText) {
  if (!prefixFormat || !prefixText) return;

  const date = new Date();
  let folderName = "";

  switch (prefixFormat) {
    case "date":
      folderName = date.toLocaleDateString("fr-FR");
      break;

    case "dateHour":
      folderName = date.toLocaleString("fr-FR");
      break;

    case "prefix":
      folderName = prefixText;
      break;

    case "prefixDate":
      folderName = prefixText + " " + date.toLocaleDateString("fr-FR");
      break;

    case "prefixDateHour":
      folderName = prefixText + " " + date.toLocaleString("fr-FR");
      break;

    default:
      folderName = date.toLocaleString("fr-FR");
  }

  return folderName;
}

async function getFoldersByPatient(id) {
  if (!id) return [];

  const res = await fetch(`/api/folders/patient/${id}`);
  const json = await res.json();

  if (!res.ok || !json.success) {
    console.error("Erreur getFolderWithPatientId", json);
    throw new Error(json.error || "Erreur lors de la récupération des dossiers du patient");
  }

  return json.folders;
}

async function getFolder(id) {
  if (!id) return null;

  const res = await fetch(`/api/folder/${id}`);
  const json = await res.json();

  if (!res.ok || !json.success) {
    console.error("Erreur getFolder", json);
    throw new Error(json.error || "Erreur lors de la récupération du dossier");
  }

  return json.folder;
}

async function createFolder(data) {
  if (!data || !data.patient_id || !data.name || !data.pdf_parameter_id) {
    throw new Error("patient_id, pdf_parameter_id et name sont obligatoires pour créer un folder");
  }

  const payload = {
    patient_id: data.patient_id,
    pdf_parameter_id: data.pdf_parameter_id,
    name: data.name,
  };

  const res = await fetch("/api/folder", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(payload),
  });

  const json = await res.json();

  if (!res.ok || !json.success) {
    console.error("Erreur createFolder", json);
    throw new Error(json.error || "Erreur lors de la création du dossier");
  }

  return json.folder;
}

async function updateFolder(data) {
  if (!data || !data.id) {
    throw new Error("L'objet dossier doit contenir un id pour être mis à jour");
  }

  const id = data.id;
  const payload = {
    patient_id: data.patient_id,
    name: data.name,
    archivedAt: data.archivedAt ?? null,
  };

  const res = await fetch(`/api/folder/${id}`, {
    method: "PUT",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(payload),
  });

  const json = await res.json();

  if (!res.ok || !json.success) {
    console.error("Erreur updateFolder", json);
    throw new Error(json.error || "Erreur lors de la mise à jour du dossier");
  }

  return json.folder;
}

async function archivedFolder(folder) {
  const payload = {
    ...folder,
    archivedAt: new Date().toISOString(),
  };

  return updateFolder(payload);
}

async function unarchivedFolder(folder) {
  const payload = {
    ...folder,
    archivedAt: null,
  };

  return updateFolder(payload);
}

async function refreshUpdatedAt(folder) {
  const payload = {
    ...folder,
    updatedAt: new Date().toISOString(),
  };

  return updateFolder(payload);
}
