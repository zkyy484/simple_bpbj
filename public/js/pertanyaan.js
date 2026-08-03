// public/js/pertanyaan.js

function openModal(url) {
    fetch(url)
        .then(res => res.text())
        .then(html => {
            document.getElementById('modalContent').innerHTML = html;
            new bootstrap.Modal(document.getElementById('mainModal')).show();
        });
}

function toggleOpsiBuilder(tipe) {
    document.getElementById('builder-rating').classList.add('d-none');
    document.getElementById('builder-pg').classList.add('d-none');
    document.getElementById('info-textarea').classList.add('d-none');

    document.getElementById('list-opsi-rating').innerHTML = '';
    document.getElementById('list-opsi-pg').innerHTML = '';

    if (tipe === 'rating') {
        document.getElementById('builder-rating').classList.remove('d-none');
        // default template 4 opsi dengan nilai berjenjang 25,50,75,100
        const defaults = [
            { opsi: 'Tidak Mudah', nilai: 25 },
            { opsi: 'Kurang Mudah', nilai: 50 },
            { opsi: 'Mudah', nilai: 75 },
            { opsi: 'Sangat Mudah', nilai: 100 },
        ];
        defaults.forEach(d => addOpsiRow('list-opsi-rating', d.opsi, d.nilai, true));
    } else if (tipe === 'pilihan_ganda') {
        document.getElementById('builder-pg').classList.remove('d-none');
        addOpsiRow('list-opsi-pg'); // satu contoh kosong
    } else if (tipe === 'textarea') {
        document.getElementById('info-textarea').classList.remove('d-none');
    }
}

let opsiIndex = 0;
function addOpsiRow(containerId, opsiVal = '', nilaiVal = '', showNilai = null) {
    const idx = opsiIndex++;
    const isRating = containerId === 'list-opsi-rating';
    const showNilaiInput = showNilai === null ? isRating : showNilai;

    const row = document.createElement('div');
    row.className = 'd-flex gap-2 mb-2 opsi-row';
    row.innerHTML = `
        <input type="text" name="opsi[${idx}][opsi]" class="form-control" placeholder="Label opsi" value="${opsiVal}" required>
        ${showNilaiInput ? `<input type="number" name="opsi[${idx}][nilai]" class="form-control" style="max-width:100px" placeholder="Nilai" value="${nilaiVal}">` : ''}
        <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">&times;</button>
    `;
    document.getElementById(containerId).appendChild(row);
}

function submitFormPertanyaan(e, url, method) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    if (method === 'PUT') formData.append('_method', 'PUT');

    fetch(url, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: formData,
    })
    .then(async res => {
        const data = await res.json();
        if (!res.ok) throw data;
        bootstrap.Modal.getInstance(document.getElementById('mainModal')).hide();
        location.reload(); // atau reload isi tabel via AJAX kalau mau tanpa reload penuh
    })
    .catch(err => {
        alert('Gagal menyimpan. Periksa kembali input.');
        console.error(err);
    });
}