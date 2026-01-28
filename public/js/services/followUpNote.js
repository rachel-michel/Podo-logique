async function getNoteByFolder(id) {
  if (!id) return [];

  const res = await fetch(`/api/follow-up-note/folder/${id}`);
  const json = await res.json();

  if (!res.ok || !json.success) {
    console.error("Erreur getNoteByFolder", json);
    throw new Error(json.error || "Erreur lors de la récupération des notes");
  }

  return json.notes;
}

async function createNote(data) {
  if (!data || !data.folder_id || !data.text) {
    throw new Error("folder_id et text sont obligatoires pour créer une note");
  }

  const payload = {
    folder_id: data.folder_id,
    text: data.text,
  };

  const res = await fetch("/api/follow-up-note", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(payload),
  });

  const json = await res.json();

  if (!res.ok || !json.success) {
    console.error("Erreur createNote", json);
    throw new Error(json.error || "Erreur lors de la création de la note");
  }

  return json.note;
}

async function updateNote(data) {
  if (!data || !data.id) {
    throw new Error("L'objet note doit contenir un id pour être mis à jour");
  }

  const id = data.id;
  const payload = {
    text: data.text,
  };

  const res = await fetch(`/api/follow-up-note/${id}`, {
    method: "PUT",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(payload),
  });

  const json = await res.json();

  if (!res.ok || !json.success) {
    console.error("Erreur updateNote", json);
    throw new Error(json.error || "Erreur lors de la mise à jour de la note");
  }

  return json.note;
}

async function deleteNote(id) {
  const res = await fetch(`/api/follow-up-note/${id}`, {
    method: "DELETE",
  });
  const json = await res.json();
  if (!json.success) throw new Error(json.error || "Erreur delete");
  return true;
}
