/**
 * Helper tabel berkelompok berdasarkan tanggal input (created_at).
 *
 * Dipakai oleh tabel Surat Masuk, Surat Keluar, dan Draft Surat Keluar
 * agar baris yang disisipkan/dihapus lewat AJAX tetap berada pada kelompok
 * tanggal yang benar dan jumlah surat per kelompok tetap akurat.
 *
 * Header kelompok dirender dari sisi server (Blade), termasuk template
 * "Hari Ini" pada elemen <template>, sehingga label tanggal tidak dibuat
 * ulang di sisi klien.
 */
const GROUP_ROW = 'tr.dtg-row';
const DATA_ROW = 'tr[data-surat-row]';

/** Hitung baris data setelah sebuah header kelompok hingga header berikutnya. */
function rowsInGroup(header) {
    let total = 0;
    let el = header ? header.nextElementSibling : null;

    while (el && !el.matches(GROUP_ROW)) {
        if (el.matches(DATA_ROW)) total += 1;
        el = el.nextElementSibling;
    }

    return total;
}

/** Perbarui label jumlah surat pada header kelompok. */
function refreshCount(header) {
    const counter = header ? header.querySelector('.dtg-count') : null;
    if (counter) counter.textContent = rowsInGroup(header) + ' surat';
}

/** Cari header kelompok terdekat di atas sebuah baris data. */
function groupHeaderOf(row) {
    let el = row ? row.previousElementSibling : null;

    while (el) {
        if (el.matches(GROUP_ROW)) return el;
        el = el.previousElementSibling;
    }

    return null;
}

/**
 * Sisipkan baris baru ke kelompok "Hari Ini" (kelompok teratas karena
 * data diurutkan dari pencatatan terbaru).
 */
function insertRow({ tbody, template, emptyRow, row }) {
    if (!tbody || !row) return;

    if (emptyRow) emptyRow.remove();

    let header = tbody.querySelector(GROUP_ROW + '[data-today="1"]');

    if (!header && template) {
        header = template.content.firstElementChild.cloneNode(true);
        tbody.insertBefore(header, tbody.firstChild);
    }

    if (header) {
        header.insertAdjacentElement('afterend', row);
        refreshCount(header);
        return;
    }

    tbody.insertBefore(row, tbody.firstChild);
}

/** Hapus baris data, buang kelompok yang menjadi kosong. */
function removeRow(row) {
    if (!row) return;

    const header = groupHeaderOf(row);
    row.remove();

    if (!header) return;

    if (rowsInGroup(header) === 0) {
        header.remove();
        return;
    }

    refreshCount(header);
}

window.DateGroupTable = { rowsInGroup, refreshCount, groupHeaderOf, insertRow, removeRow };
