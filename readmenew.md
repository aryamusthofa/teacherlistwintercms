# Rekap Proyek: Armuzz Project Dev

Dokumen ini jadi satu-satunya tempat rekap: struktur proyek, fitur yang sudah jadi, plugin yang dipakai, dan daftar theme.

## Ringkasan
- CMS: Winter CMS
- Fokus: theme frontend + CRUD Teachers
- Folder theme utama yang dipakai di localhost: `themes/armuzzthemes/`

## Struktur Theme Utama (`themes/armuzzthemes/`)
### Halaman (Pages)
- `welcome` -> `/`
- `dashboard` -> `/dashboard`
- `teachers` -> `/teachers`
- `teachers_create` -> `/teachers/create`
- `teachers_edit` -> `/teachers/:id/edit`

### Partial (Partials)
- `partials/navbar.htm`
- `partials/footer.htm`
- `partials/flash.htm`
- `partials/pagination.htm`
- `partials/teachers/form.htm`
- `partials/teachers/table.htm`

## CRUD Teachers (Detail)
Target tabel: `latihan_teachers` (disediakan oleh plugin `Latihan.Latihan`).

### List + Filter + Pagination (`pages/teachers.htm`)
- Query via `Db::table('latihan_teachers')`
- Search (`q`) untuk `name` dan `subject`
- Filter status: `active` / `inactive` / `all`
- Pagination manual: 10 item / halaman
- Guard: `Schema::hasTable('latihan_teachers')` (kalau belum ada, tampilkan alert)

Handler:
- `onDelete` -> hapus teacher by `id`
- `onToggleActive` -> toggle `is_active` + update `updated_at`

### Create (`pages/teachers_create.htm`)
- Validasi input (name, subject, is_active)
- Insert record ke `latihan_teachers`
- Flash message + redirect ke list

### Edit (`pages/teachers_edit.htm`)
- Load teacher dari param `:id`
- Validasi input
- Update record + flash message

## Plugin & Database
### Plugin utama
- `Latihan.Latihan` → migration & tabel `latihan_teachers`

### Struktur tabel (ringkas)
- `id`
- `name`
- `subject`
- `is_active`
- `created_at`
- `updated_at`

## Lokalisasi (Bahasa)
Project sudah memakai paket bahasa Indonesia (`id`) untuk modul inti (Backend / System / CMS) dan beberapa vendor.

## Themes (Daftar)
Catatan: theme aktif hanya satu pada satu waktu. Theme lain aman sebagai alternatif/opsional.

### Theme utama (dipakai)
| Theme | Folder | Catatan |
|---|---|---|
| ArmuzzThemes | `themes/armuzzthemes/` | Default untuk localhost, Bootstrap 5, CRUD Teachers |

### 6 Theme baru (basisnya `armuzzthemes`)
Semua theme di bawah ini menyalin struktur `armuzzthemes` (pages/partials sama), lalu menambahkan styling via `assets/css/style.css` + penyesuaian layout.

| Theme | Folder | Vibe | Bootstrap |
|---|---|---|---|
| Ocean | `themes/ocean/` | Deep blue + aqua accents (dark) | Ya |
| Forest | `themes/forest/` | Green earthy (dark) | Ya |
| Sunset | `themes/sunset/` | Warm gradient (light) | Ya |
| Nord | `themes/nord/` | Nordic muted blues (dark) | Ya |
| Glass | `themes/glass/` | Frosted glass / translucent (dark) | Ya |
| Mono | `themes/mono/` | Monokrom editorial (light) | Ya |

Catatan tambahan:
- Di 6 theme baru, halaman demo yang tidak relevan (mis. `home/ajax/plugins/404/error`) sengaja tidak disertakan supaya fokusnya tetap ke halaman `welcome/dashboard/teachers`.

### Theme lain (existing/legacy)
Theme berikut sudah ada dari sebelumnya (anggap sebagai referensi/arsip kalau tidak dipakai):
- `themes/cyberpunk/`
- `themes/dark/`
- `themes/light/`
- `themes/minimalist/`
- `themes/rgb/`
- `themes/vintage/`
- `themes/demo/`

## Cara ganti theme aktif
Opsi umum di Winter CMS (tergantung setup project):
1) Via Backend: masuk ke CMS/Themes lalu "Activate" theme yang dipilih.
2) Via konfigurasi: cari pengaturan "active theme" (mis. `activeTheme`) lalu set ke nama folder theme.

## Progress Checklist
| Fitur | Status | Keterangan |
|---|:---:|---|
| Setup Winter CMS | [OK] | Environment dasar berjalan |
| Lokalisasi ID | [OK] | Bahasa Indonesia terpasang |
| List Teachers (Search/Filter/Pagination) | [OK] | Sudah ada |
| Delete Teacher | [OK] | Handler `onDelete` |
| Toggle Active/Inactive | [OK] | Handler `onToggleActive` |
| Create Teacher | [OK] | `teachers_create` + validasi |
| Edit Teacher | [OK] | `teachers_edit` + validasi |
| Refactor ke Model | [TODO] | Masih Query Builder di page, idealnya pindah ke model plugin |
