


function filterTable(tableId, filters) {
    const table = document.getElementById(tableId);
    if (!table) return;

    const rows = table.querySelectorAll('tbody tr');
    rows.forEach(row => {
        let show = true;
        filters.forEach(({ inputId, colIndex }) => {
            const input = document.getElementById(inputId);
            if (!input) return;
            const val = input.value.toLowerCase().trim();
            if (!val) return;
            const cell = row.cells[colIndex];
            if (!cell || !cell.textContent.toLowerCase().includes(val)) {
                show = false;
            }
        });
        row.style.display = show ? '' : 'none';
    });
}


function resetFilter(formId, tableId) {
    const form = document.getElementById(formId);
    if (form) {
        const inputs = form.querySelectorAll('input, select');
        inputs.forEach(el => {
            if (el.type === 'checkbox' || el.type === 'radio') {
                el.checked = false;
            } else {
                el.value = '';
            }
        });
    }
    if (tableId) {
        const table = document.getElementById(tableId);
        if (table) {
            table.querySelectorAll('tbody tr').forEach(r => r.style.display = '');
        }
    }
}


function setupSearchFilter(inputId, tableId) {
    const input = document.getElementById(inputId);
    const table = document.getElementById(tableId);
    if (!input || !table) return;

    input.addEventListener('input', function () {
        const val = this.value.toLowerCase().trim();
        table.querySelectorAll('tbody tr').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(val) ? '' : 'none';
        });
    });
}


function exportTableToCSV(tableId, filename) {
    const table = document.getElementById(tableId);
    if (!table) { alert('Tabel tidak ditemukan.'); return; }

    const rows = [];
    const headers = [];

    function formatCsvCell(text, headerText) {
        const cleanText = (text || '').trim().replace(/\s+/g, ' ');
        const normalizedHeader = (headerText || '').toLowerCase();
        const shouldKeepAsText =
            normalizedHeader.includes('tanggal') ||
            normalizedHeader.includes('waktu') ||
            normalizedHeader.includes('nik') ||
            normalizedHeader.includes('telepon') ||
            /^\d{4}-\d{2}-\d{2}/.test(cleanText) ||
            /^\d{2}:\d{2}/.test(cleanText);
        const value = shouldKeepAsText && cleanText !== '' ? '\t' + cleanText : cleanText;
        return '"' + value.replace(/"/g, '""') + '"';
    }

    
    table.querySelectorAll('thead th').forEach(th => {
        const text = th.textContent.trim();
        if (text.toLowerCase() !== 'aksi') {
            headers.push('"' + text.replace(/"/g, '""') + '"');
        }
    });
    rows.push(headers.join(','));

    
    table.querySelectorAll('tbody tr').forEach(tr => {
        if (tr.style.display === 'none') return;
        const cells = [];
        const ths = table.querySelectorAll('thead th');
        let tdIndex = 0;
        ths.forEach((th, i) => {
            const headerText = th.textContent.trim().toLowerCase();
            if (headerText === 'aksi') { tdIndex++; return; }
            const td = tr.cells[tdIndex];
            if (td) {
                cells.push(formatCsvCell(td.textContent, headerText));
            }
            tdIndex++;
        });
        if (cells.length > 0) rows.push(cells.join(','));
    });

    const csvContent = '\uFEFF' + rows.join('\n'); 
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const url  = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.setAttribute('href', url);
    link.setAttribute('download', filename || 'export_' + new Date().toISOString().split('T')[0] + '.csv');
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    URL.revokeObjectURL(url);
}


function confirmHapus(message, formId) {
    if (confirm(message || 'Apakah Anda yakin ingin menghapus data ini?')) {
        const form = document.getElementById(formId);
        if (form) form.submit();
    }
}
