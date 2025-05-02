import jsPDF from "jspdf";
import autoTable from "jspdf-autotable";

document.addEventListener('DOMContentLoaded', () => {
  const exportPdfBtn = document.getElementById("exportPdf");
  if (!exportPdfBtn) return;

  exportPdfBtn.addEventListener("click", () => {
    const doc = new jsPDF();
    doc.text("Order list", 14, 15);

    const table = document.getElementById("ordersTable");
    const headers = Array.from(table.querySelectorAll("thead th")).map(th => th.innerText.trim());
    const rows = Array.from(table.querySelectorAll("tbody tr")).map(tr =>
      Array.from(tr.querySelectorAll("td")).map(td => td.innerText.trim())
    );

    autoTable(doc, {
      head: [headers],
      body: rows,
      startY: 20,
      styles: { fontSize: 8 },
      headStyles: { fillColor: [52, 58, 64] },
    });

    doc.save("commandes.pdf");
  });
});
