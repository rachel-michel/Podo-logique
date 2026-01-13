async function getAllPrescriber() {
  const res = await fetch("/api/prescribers");
  const json = await res.json();
  if (!json.success) throw new Error(json.error || "Erreur list");
  return json.prescribers;
}

async function createPrescriber(data) {
  const res = await fetch("/api/prescriber", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(data),
  });
  const json = await res.json();
  if (!json.success) throw new Error(json.error || "Erreur create");
  return json.prescriber;
}

async function updatePrescriber(data) {
  const res = await fetch(`/api/prescriber/${data.id}`, {
    method: "PUT",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(data),
  });
  const json = await res.json();
  if (!json.success) throw new Error(json.error || "Erreur update");
  return json.prescriber;
}

async function deletePrescriber(id) {
  const res = await fetch(`/api/prescriber/${id}`, {
    method: "DELETE",
  });
  const json = await res.json();
  if (!json.success) throw new Error(json.error || "Erreur delete");
  return true;
}
