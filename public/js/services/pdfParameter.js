async function getGlobalPdfParameter() {
  const res = await fetch("/api/pdf-parameter/global");
  const json = await res.json();

  if (!res.ok || !json.success) {
    console.error("Erreur getGlobalPdfParameter", json);
    throw new Error(json.error || "Erreur lors de la récupération des paramètres PDF globaux");
  }

  return json.pdfParameter;
}

async function getPdfParameterByFolder(id) {
  if (!id) return null;

  const res = await fetch(`/api/pdf-parameter/folder/${id}`);
  const json = await res.json();
  return json.pdfParameter || null;
}

async function createPdfParameter(data) {
  if (!data) {
    throw new Error("Les données sont requises pour createPdfParameter");
  }

  const res = await fetch("/api/pdf-parameter", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(data),
  });

  const json = await res.json();

  if (!res.ok || !json.success) {
    console.error("Erreur createPdfParameter", json);
    throw new Error(json.error || "Erreur lors de la création des paramètres PDF");
  }

  return json.pdfParameter;
}

async function updatePdfParameter(data) {
  if (!data || !data.id) {
    throw new Error("Les données doivent contenir un id pour updatePdfParameter");
  }

  const id = data.id;

  const res = await fetch(`/api/pdf-parameter/${id}`, {
    method: "PUT",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(data),
  });

  const json = await res.json();

  if (!res.ok || !json.success) {
    console.error("Erreur updatePdfParameter", json);
    throw new Error(json.error || "Erreur lors de la mise à jour des paramètres PDF");
  }

  return json.pdfParameter;
}
