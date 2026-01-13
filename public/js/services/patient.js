async function getAllPatient() {
  const res = await fetch("/api/patients");
  const json = await res.json();
  if (!json.success) throw new Error(json.error || "Erreur list");
  return json.patients;
}

async function getPatient(id) {
  const res = await fetch(`/api/patient/${id}`);
  const json = await res.json();
  if (!json.success) throw new Error(json.error || "Erreur get");
  return json.patient;
}

async function createPatient(data) {
  const res = await fetch("/api/patient", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(data),
  });
  const json = await res.json();
  if (!json.success) throw new Error(json.error || "Erreur create");
  return json.patient;
}

async function updatePatient(id, data) {
  const res = await fetch(`/api/patient/${id}`, {
    method: "PUT",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(data),
  });
  const json = await res.json();
  if (!json.success) throw new Error(json.error || "Erreur update");
  return json.patient;
}
