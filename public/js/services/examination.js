async function getExaminationByFolder(id) {
  if (!id) return [];

  const res = await fetch(`/api/examinations/folder/${id}`);
  const json = await res.json();

  if (!res.ok || !json.success) {
    console.error("Erreur getExaminationByFolder", json);
    throw new Error(json.error || "Erreur lors de la récupération des examinations");
  }

  return json.examinations;
}

async function createExamination(data) {
  if (!data || !data.folder_id || !data.name) {
    throw new Error("folder_id et name sont obligatoires pour créer un examination");
  }

  const payload = {
    folder_id: data.folder_id,
    name: data.name,
    localisation: data.localisation,
    observation: data.observation,
  };

  const res = await fetch("/api/examinations", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(payload),
  });

  const json = await res.json();

  if (!res.ok || !json.success) {
    console.error("Erreur createExamination", json);
    throw new Error(json.error || "Erreur lors de la création du examination");
  }

  return json.examination;
}

async function updateExamination(data) {
  if (!data || !data.id) {
    throw new Error("L'objet examination doit contenir un id pour être mis à jour");
  }

  const id = data.id;
  const payload = {
    name: data.name,
    localisation: data.localisation,
    observation: data.observation,
  };

  const res = await fetch(`/api/examination/${id}`, {
    method: "PUT",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(payload),
  });

  const json = await res.json();

  if (!res.ok || !json.success) {
    console.error("Erreur updateExamination", json);
    throw new Error(json.error || "Erreur lors de la mise à jour du de l'examination");
  }

  return json.examination;
}

async function deleteExamination(id) {
  const res = await fetch(`/api/examination/${id}`, {
    method: "DELETE",
  });
  const json = await res.json();
  if (!json.success) throw new Error(json.error || "Erreur delete");
  return true;
}
