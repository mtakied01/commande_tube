import * as XLSX from 'xlsx';

document.addEventListener('DOMContentLoaded', () => {
  const exportBtn = document.getElementById('exportExcel');

  exportBtn?.addEventListener('click', () => {
    const table = document.getElementById('ordersTable');
    const headers = Array.from(table.querySelectorAll('thead th')).map(th => th.innerText.trim());

    const data = [headers];

    const rows = Array.from(table.querySelectorAll('tbody tr'));
    rows.forEach(tr => {
      const row = Array.from(tr.querySelectorAll('td')).map(td => td.innerText.trim());
      data.push(row);
    });

    const worksheet = XLSX.utils.aoa_to_sheet(data);

    const range = XLSX.utils.decode_range(worksheet['!ref']);

    for (let R = range.s.r + 1; R <= range.e.r; ++R) {
      const retardCellAddress = XLSX.utils.encode_cell({ r: R, c: 6 });

      const retardValue = Number(worksheet[retardCellAddress]?.v);
      if (!isNaN(retardValue)) {
        worksheet[retardCellAddress].s = {
          fill: {
            fgColor: { rgb: retardValue >= 2 ? 'FF0000' : '00FF00' }
          },
          border: {
            top: { style: "thin", color: { auto: 1 } },
            bottom: { style: "thin", color: { auto: 1 } },
            left: { style: "thin", color: { auto: 1 } },
            right: { style: "thin", color: { auto: 1 } },
          },
        };
      }
    }

    for (let R = range.s.r; R <= range.e.r; ++R) {
      for (let C = range.s.c; C <= range.e.c; ++C) {
        const cellAddress = XLSX.utils.encode_cell({ r: R, c: C });
        if (!worksheet[cellAddress]) continue;
        worksheet[cellAddress].s = worksheet[cellAddress].s || {};
        worksheet[cellAddress].s.border = {
          top: { style: "thin", color: { auto: 1 } },
          bottom: { style: "thin", color: { auto: 1 } },
          left: { style: "thin", color: { auto: 1 } },
          right: { style: "thin", color: { auto: 1 } },
        };
      }
    }

    worksheet['!cols'] = headers.map(() => ({ wch: 20 }));

    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, 'Commandes');

    XLSX.writeFile(workbook, 'commandes_formatees.xlsx');
  });
});
