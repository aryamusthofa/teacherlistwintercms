Berikut adalah file Markdown (.md) yang berisi kode dan penjelasan untuk fitur "Delete Button" (yang kamu sebut sebagai "antigravity" karena efeknya menghilangkan baris data) berdasarkan tangkapan layar dan transkrip rapat yang kamu unggah.

Markdown
# Implementation: Delete Button Logic (Teacher List)

Dokumen ini berisi snippet kode untuk tombol *Delete* pada tabel guru (Teacher List) menggunakan Winter CMS Framework (AJAX Handler).

## 1. Lokasi File
Pastikan kode ini ditempatkan di dalam file view komponen:
* [cite_start]**Path:** `plugins/latihan/latihan/components/teacherlist/default.htm` [cite: 53]

## 2. Code Snippet
Berikut adalah kode lengkap untuk tombol delete yang sedang diketik pada baris 27-33 di dalam screenshot:

```html
<button
    type="button"
    class="btn btn-sm btn-danger"
    data-request="{{ __SELF__ }}::onDelete"
    data-request-data="id: {{ t.id }}"
    data-request-confirm="Yakin ingin menghapus data guru: {{ t.name }}?"
    data-request-success="$(this).closest('tr').remove()"
>
    Delete
</button>
Catatan: Pada screenshot, baris data-request-success belum selesai diketik ("$(this).|"). Kode di atas telah dilengkapi dengan logika standar .closest('tr').remove() agar baris tabel menghilang tanpa refresh halaman.

3. Penjelasan Atribut (Logic Breakdown)
Berikut adalah penjelasan fungsi dari setiap atribut berdasarkan diskusi meeting:

data-request="{{ __SELF__ }}::onDelete"

Atribut ini berfungsi memanggil AJAX handler.


{{ __SELF__ }} mengacu pada komponen itu sendiri (TeacherList).


onDelete adalah nama function handler yang ada di file backend TeacherList.php.

data-request-data="id: {{ t.id }}"

Berfungsi mengirimkan data spesifik ke backend menggunakan metode POST.


id: {{ t.id }} memastikan bahwa request membawa ID guru yang berada di baris tersebut, sehingga server tahu data mana yang harus dihapus.

data-request-confirm="..."

Menampilkan browser alert (pop-up) untuk konfirmasi sebelum request dijalankan.

Pesan: "Yakin ingin menghapus data guru: [Nama Guru]?".

data-request-success="$(this).closest('tr').remove()"

Ini adalah bagian visual (Front End). Jika request sukses, perintah JavaScript/jQuery ini akan dijalankan.

Fungsinya mencari elemen <tr> (baris tabel) terdekat dari tombol yang diklik dan menghapusnya dari tampilan HTML.

4. Prasyarat
Agar tombol ini berfungsi ("terbang"/hilang), pastikan:


jQuery & Framework Extras sudah dimuat di layout utama (default.htm layout).
+1

Komponen TeacherList sudah dipasang di halaman home.htm.
+1