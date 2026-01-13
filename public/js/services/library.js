async function getAllSuggestion() {
  const res = await fetch("/api/libraries");
  const json = await res.json();
  if (!json.success) throw new Error(json.error || "Erreur list");
  return json.libraries;
}

async function createSuggestion(data) {
  const res = await fetch("/api/library", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(data),
  });
  const json = await res.json();
  if (!json.success) throw new Error(json.error || "Erreur create");
  return json.library;
}

async function deleteSuggestion(id) {
  const res = await fetch(`/api/library/${id}`, {
    method: "DELETE",
  });
  const json = await res.json();
  if (!json.success) throw new Error(json.error || "Erreur delete");
  return true;
}
