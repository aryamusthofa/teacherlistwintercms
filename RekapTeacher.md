# 📚 Rekap Lengkap Teachers Plugin - Armuzz Dev

## 🎯 Tujuan Website & Plugin

Website ini adalah **Dashboard Management Sekolah berbasis Winter CMS** dengan fokus pada manajemen data guru (Teachers). Tujuannya adalah menyediakan sistem CRUD (Create, Read, Update, Delete) yang lengkap dan user-friendly untuk mengelola informasi guru dengan watermark branding Armuzz Dev di seluruh halaman.

### Visi
Membangun sistem management data guru yang:
- ✅ Mudah digunakan (User-friendly)
- ✅ Responsif dan modern
- ✅ Memiliki branding yang jelas (Watermark Armuzz Dev)
- ✅ Mendukung multiple themes
- ✅ Secure dan properly validated

---

## 📁 Struktur Lengkap Plugin Teachers

### **Root Directory: `/plugins/latihan/latihan/`**

```
plugins/latihan/latihan/
├── Plugin.php                          # Main plugin class dengan registrasi menu & assets
├── composer.json                       # Dependensi PHP
├── package.json                        # Metadata plugin
├── LICENSE                             # Lisensi plugin
├── README.md                           # Dokumentasi plugin
├── phpunit.xml                         # Testing configuration
├── routes.php                          # Custom routes (opsional)
├── ServiceProvider.php                 # Service provider untuk plugin
│
├── assets/                             # Static assets (CSS, JS, images)
│   ├── css/
│   │   └── backend-watermark.css      # CSS untuk watermark di backend
│   └── js/
│       ├── backend-watermark.js       # JS untuk inject watermark di halaman backend
│       └── list-edit-selected.js      # JS untuk Edit Selected button (sudah deprecated)
│
├── controllers/                        # Backend controllers
│   ├── Teachers.php                    # Main controller untuk Teachers list & form
│   └── teachers/                       # Teacher controller views & configs
│       ├── config_list.yaml            # Konfigurasi list Teachers (kolom, search, toolbar, etc)
│       ├── config_form.yaml            # Konfigurasi form create/update (field definitions)
│       ├── _toolbar.htm                # Custom toolbar dengan tombol Create, Delete, Edit Selected
│       ├── index.htm                   # View untuk list page
│       ├── create.htm                  # View untuk form create/tambah data
│       └── update.htm                  # View untuk form edit/update data
│
├── models/                             # Database models
│   ├── Teacher.php                     # Model untuk tabel teachers
│   └── teacher/                        # Teacher model configs
│       └── columns.yaml                # Definisi kolom tabel untuk list view
│
├── updates/                            # Database migrations
│   ├── version.yaml                    # Version tracking untuk migrations
│   ├── create_teachers_table.php       # Migration: buat tabel teachers
│   └── add_is_verified_to_teachers_table.php  # Migration: tambah kolom is_verified
│
├── components/                         # Frontend components (Vue, partials, dll)
│   └── teacherlist/
│       └── TeacherList.php             # Component untuk display teachers di frontend
│
├── lang/                               # Localization files
│   └── en/
│       └── lang.php                    # English language strings (opsional)
│
└── [Other dirs: behaviors/, console/, database/, facades/, formwidgets/, helpers/, 
     reportwidgets/, skins/, tests/, traits/, views/, widgets/]
     # Struktur Winter CMS standard (mostly empty di plugin ini)
```

---

## 📋 File-File Penting & Penjelasannya

### **1. Plugin.php** - Main Plugin Class
```php
<?php namespace Latihan\Latihan;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function pluginDetails()
    {
        // Metadata plugin - nama, deskripsi, author, icon
        return [
            'name'        => 'Latihan',
            'description' => 'Plugin latihan untuk dashboard guru dinamis',
            'author'      => 'Latihan',
            'icon'        => 'icon-leaf'
        ];
    }

    public function registerComponents()
    {
        // Register frontend components untuk digunakan di halaman/partial
        return [
            \Latihan\Latihan\Components\TeacherList::class => 'teacherList',
        ];
    }

    public function registerNavigation()
    {
        // Register menu di backend sidebar
        return [
            'main-menu-latihan' => [
                'label' => 'Data Sekolah',
                'url' => \Backend::url('latihan/latihan/teachers'),
                'icon' => 'icon-graduation-cap',
                'order' => 500,
                'sideMenu' => [
                    'side-menu-teachers' => [
                        'label' => 'Teachers',
                        'icon' => 'icon-users',
                        'url' => \Backend::url('latihan/latihan/teachers'),
                    ]
                ]
            ]
        ];
    }
    
    public function boot()
    {
        // Hook untuk inject assets di backend pages
        \Event::listen('backend.page.beforeDisplay', function($controller, $action, $params) {
            if (method_exists($controller, 'addCss')) {
                $controller->addCss('/plugins/latihan/latihan/assets/css/backend-watermark.css');
            }
            if (method_exists($controller, 'addJs')) {
                $controller->addJs('/plugins/latihan/latihan/assets/js/backend-watermark.js');
            }
        });
    }
}
```

**Fungsi:**
- Mendefinisikan metadata plugin
- Registrasi menu di backend sidebar
- Inject watermark assets ke halaman backend
- Registrasi frontend components

---

### **2. Teachers.php** - Controller
```php
<?php namespace Latihan\Latihan\Controllers;

use Backend\Classes\Controller;
use Backend\Facades\BackendMenu;

class Teachers extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController'
    ];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Latihan.Latihan', 'main-menu-latihan', 'side-menu-teachers');
    }
}
```

**Fungsi:**
- Mengimplementasikan ListController (untuk list/CRUD operations)
- Mengimplementasikan FormController (untuk create/update forms)
- Set active menu context di backend sidebar
- Point ke config files untuk list dan form configuration

---

### **3. config_list.yaml** - List Configuration
```yaml
title: Teachers List
modelClass: Latihan\Latihan\Models\Teacher
list: $/latihan/latihan/models/teacher/columns.yaml
showCheckboxes: true
toolbar:
    buttons: toolbar
recordUrl: update/{:id}
search:
    prompt: backend::lang.list.search_prompt
```

**Penjelasan:**
- `title`: Judul halaman list
- `modelClass`: Model yang digunakan
- `list`: Path ke file columns.yaml (definisi kolom tabel)
- `showCheckboxes: true`: Tampilkan checkbox untuk multi-select
- `toolbar.buttons: toolbar`: Gunakan custom toolbar dari `_toolbar.htm`
- `recordUrl`: Klik baris akan membuka form edit dengan ID dari record
- `search`: Enable search functionality

---

### **4. config_form.yaml** - Form Configuration
```yaml
modelClass: Latihan\Latihan\Models\Teacher
form:
  fields:
    name:
      label: Name
      type: text
      span: auto
    subject:
      label: Subject
      type: text
      span: auto
    is_active:
      label: Active
      type: switch
      default: 1
    is_verified:
      label: Verified
      type: switch
      default: 0
```

**Penjelasan:**
- `modelClass`: Model yang digunakan (REQUIRED untuk FormController)
- `form.fields`: Definisi field yang ditampilkan di form
  - `type: text`: Input text biasa
  - `type: switch`: Toggle switch (boolean)
  - `span: auto`: Auto width di form
  - `default`: Nilai default saat create

---

### **5. columns.yaml** - Table Columns Definition
```yaml
columns:
    name:
        label: Teacher Name
        type: text
        searchable: true
        sortable: true
    subject:
        label: Subject
        type: text
        searchable: true
        sortable: true
    is_active:
        label: Status
        type: switch
        sortable: true
    is_verified:
        label: Verified
        type: switch
        sortable: true
```

**Penjelasan:**
- Definisi kolom yang ditampilkan di list table
- `searchable: true`: Kolom bisa dicari
- `sortable: true`: Kolom bisa di-sort
- `type: text/switch`: Tipe data yang ditampilkan
- `label`: Header text di tabel

---

### **6. _toolbar.htm** - Custom Toolbar View
```htm
<div class="button-strip">
    <a class="btn btn-primary" href="<?= Backend::url('latihan/latihan/teachers/create') ?>" data-request="onCreate">
        <i class="icon-plus"></i> New Teachers List
    </a>
    
    <button class="btn btn-secondary" data-request="onDelete" data-request-confirm="Hapus data yang dipilih?">
        <i class="icon-trash"></i> Delete selected
    </button>
    
    <button class="btn btn-secondary" id="edit-selected-btn" onclick="editSelected()">
        <i class="icon-edit"></i> Edit Selected
    </button>
</div>

<script>
function editSelected(){
    // Function untuk handle Edit Selected button
    var form = document.querySelector('form[data-request]');
    if (!form) {
        alert('Form tidak ditemukan');
        return;
    }
    
    var checked = form.querySelectorAll('input[type="checkbox"]:checked');
    var ids = [];
    
    checked.forEach(function(ch){
        var val = ch.value || ch.getAttribute('data-id');
        if (val) ids.push(val);
    });
    
    if (ids.length === 0) {
        alert('Pilih minimal satu record untuk diedit.');
        return;
    }
    
    // Redirect ke form edit untuk ID pertama yang dipilih
    window.location.href = '<?= Backend::url('latihan/latihan/teachers/update') ?>/' + ids[0];
}
</script>
```

**Fungsi:**
- Render custom toolbar dengan 3 tombol:
  1. **New Teachers List** (Create) - Buka form create
  2. **Delete selected** - Hapus record yang dipilih (built-in behavior)
  3. **Edit Selected** - Edit record yang dipilih

---

### **7. create.htm & update.htm** - Form Views
```php
<?php
    $this->addCss('/modules/backend/formwidgets/richeditor/assets/css/richeditor.css');
?>

<div class="form-container">
    <form onsubmit="return oc.handleForm(event, {
        redirect: '<?= Backend::url('latihan/latihan/teachers') ?>'
    })">
        <?= $this->formRender() ?>
        <div class="form-buttons" style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #ddd;">
            <button type="submit" class="btn btn-primary">
                <i class="icon-save"></i> Simpan
            </button>
            <a href="<?= Backend::url('latihan/latihan/teachers') ?>" class="btn btn-secondary">
                <i class="icon-times"></i> Batal
            </a>
        </div>
    </form>
</div>
```

**Penjelasan:**
- `<?= $this->formRender() ?>`: Render form fields berdasarkan config_form.yaml
- `onsubmit="return oc.handleForm(...)"`: Handle form submission dengan AJAX
- `redirect`: Redirect ke halaman list setelah submit
- Tombol Save & Cancel

---

### **8. Teacher.php** - Model
```php
<?php namespace Latihan\Latihan\Models;

use Model;

class Teacher extends Model
{
    protected $table = 'latihan_teachers';

    protected $fillable = [
        'name',
        'subject',
        'is_active',
        'is_verified'
    ];
}
```

**Penjelasan:**
- `protected $table`: Nama tabel di database
- `protected $fillable`: Field yang bisa di-mass-assign (protect dari injection)

---

### **9. Migrations** - Database Schema

**create_teachers_table.php:**
```php
Schema::create('latihan_teachers', function ($table) {
    $table->engine = 'InnoDB';
    $table->increments('id');
    $table->string('name');
    $table->string('subject');
    $table->boolean('is_active')->default(true);
    $table->timestamps();
    // Note: is_verified ditambahkan di migration terpisah
});
```

**add_is_verified_to_teachers_table.php:**
```php
Schema::table('latihan_teachers', function (Blueprint $table) {
    $table->boolean('is_verified')->default(false)->after('is_active');
});
```

**Struktur Tabel Database:**
```
latihan_teachers
├── id              (Integer, PK, Auto Increment)
├── name            (String) - Nama guru
├── subject         (String) - Mata pelajaran
├── is_active       (Boolean, default: true) - Status aktif
├── is_verified     (Boolean, default: false) - Status terverifikasi
├── created_at      (Timestamp)
└── updated_at      (Timestamp)
```

---

### **10. Watermark Assets**

**backend-watermark.css:**
```css
#latihan-backend-watermark {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    pointer-events: none;
    z-index: 9999;
    opacity: 0.4;
    display: flex;
    align-items: center;
    justify-content: center;
}

#latihan-backend-watermark .latihan-wm-content {
    color: rgba(255, 255, 255, 1);
    text-align: center;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif;
}

/* Styling untuk text watermark */
#latihan-backend-watermark .latihan-wm-name {
    font-weight: 700;
    font-size: 24px;
}

#latihan-backend-watermark .latihan-wm-role {
    font-size: 13px;
    font-style: italic;
}

/* Hide on mobile */
@media (max-width: 768px) {
    #latihan-backend-watermark {
        display: none;
    }
}
```

**backend-watermark.js:**
```javascript
(function(){
  document.addEventListener('DOMContentLoaded', function(){
    if (document.getElementById('latihan-backend-watermark')) return;

    var wm = document.createElement('div');
    wm.id = 'latihan-backend-watermark';
    wm.innerHTML = `
      <div class="latihan-wm-content">
        <div class="latihan-wm-name">Arya Musthofa</div>
        <div class="latihan-wm-sub">Asal Jateng — Armuzz Dev</div>
        <div class="latihan-wm-role">Software Engineering & AI Engineering</div>
        <div class="latihan-wm-social">@galeri_armus</div>
        <div class="latihan-wm-copy">©2026 Hak Cipta Dilindungi Pencipta</div>
      </div>
    `;

    document.body.appendChild(wm);
  });
})();
```

---

## 🌐 Frontend Watermark (All Themes)

Watermark juga ditambahkan ke **semua theme** di file `themes/*/partials/watermark.htm` dengan:
- **Posisi:** Center of page
- **Opacity:** 35-45% (tergantung theme)
- **Z-index:** 1 (tidak block interaction)
- **Responsive:** Hidden di mobile devices
- **Theme-aware:** Warna menyesuaikan dengan setiap theme (14 themes total)

Themes yang mendapat watermark:
- armuzzthemes, dark, light, minimalist, mono
- nord, ocean, rgb, sunset, vintage
- glass, cyberpunk, forest, demo

---

## ✨ Fitur-Fitur yang Ditambahkan Hari Ini

### **1. Teachers CRUD System**
- ✅ **List View** dengan checkbox multi-select
- ✅ **Create** - Tambah teacher baru via form
- ✅ **Read** - View teacher data di list dengan search & sort
- ✅ **Update** - Edit teacher data via form
- ✅ **Delete** - Hapus single atau multiple records

### **2. Custom Toolbar**
- ✅ **New Teachers List** button (Create)
- ✅ **Delete selected** button (multi-delete)
- ✅ **Edit Selected** button (edit first selected record)

### **3. Form Management**
- ✅ **Create Form** (`create.htm`) dengan tombol Simpan/Batal
- ✅ **Update Form** (`update.htm`) dengan tombol Simpan/Batal
- ✅ Form validation via FormController
- ✅ Redirect to list setelah submit

### **4. Watermark Branding**
- ✅ **Frontend Watermark** - Di semua halaman website (14 themes)
  - Nama: Arya Musthofa
  - Asal: Jateng
  - Brand: Armuzz Dev
  - Tagline: Software Engineering & AI Engineering
  - Social: @galeri_armus (Instagram)
  - Copyright: ©2026 Hak Cipta Dilindungi Pencipta
  - Opacity: 35-45% (adaptive per theme)
  
- ✅ **Backend Watermark** - Di halaman admin
  - Same content as frontend
  - Fixed center position
  - 40% opacity

### **5. Database Structure**
- ✅ Migration 1: Create `latihan_teachers` table
  - id, name, subject, is_active, timestamps
- ✅ Migration 2: Add `is_verified` column
  - Safely add column dengan default false

### **6. Model & Fillable**
- ✅ Teacher model dengan proper `$fillable` array
- ✅ Support untuk semua field (name, subject, is_active, is_verified)

### **7. Navigation Menu**
- ✅ Main menu: "Data Sekolah" dengan icon graduation-cap
- ✅ Side menu: "Teachers" dengan icon users
- ✅ Proper menu context dalam controller

---

## 🔧 Teknologi & Dependencies

### **Backend:**
- Winter CMS Framework
- PHP 7.4+
- Laravel-based ORM (Eloquent)
- Winter Form Builder & List Builder

### **Frontend Themes:**
- Bootstrap 5.3.3
- Bootstrap Icons 1.11.3
- Custom CSS per theme
- Responsive design

### **Database:**
- MySQL/MariaDB (InnoDB)
- Migrations untuk version control

---

## 📊 Data Flow

### **Create Teacher:**
1. User klik "New Teachers List" button
2. System buka `/backend/latihan/latihan/teachers/create`
3. Form render berdasarkan `config_form.yaml`
4. User isi fields (name, subject, is_active, is_verified)
5. User klik "Simpan"
6. `oc.handleForm()` submit data via AJAX
7. FormController validate & save ke database
8. Redirect ke list view
9. Data baru muncul di table

### **Read Teachers:**
1. User buka `/backend/latihan/latihan/teachers`
2. ListController query teachers dari database
3. Data di-render ke table sesuai `columns.yaml`
4. Search & sort functionality available

### **Update Teacher:**
1. User klik baris di table, atau
2. Centang checkbox + klik "Edit Selected"
3. System buka `/backend/latihan/latihan/teachers/update/{id}`
4. Form pre-populate dengan data existing
5. User edit fields
6. User klik "Simpan"
7. FormController validate & update database
8. Redirect ke list view

### **Delete Teacher:**
1. User centang satu/multiple checkbox
2. User klik "Delete selected"
3. System confirm deletion
4. Delete dari database
5. List auto-refresh

---

## 🎨 Watermark Details

### **Frontend Watermark (Themes):**
- **Position:** Center of page
- **Fixed:** Yes (tetap di tempat saat scroll)
- **Opacity:** 35-45% (0.35-0.45 alpha)
- **Font:** System fonts (-apple-system, BlinkMacSystemFont, Segoe UI, Roboto)
- **Size:** Responsive (hide di mobile <768px)
- **Z-index:** 1 (tidak block content)
- **Pointer-events:** none (tidak intercept clicks)

### **Theme Color Adaptation:**
Setiap theme punya warna watermark yang sesuai:
- **light** → rgba(0, 0, 0, 0.40)
- **dark** → rgba(255, 255, 255, 0.38)
- **cyberpunk** → rgba(0, 255, 255, 0.38) - cyan accent
- **ocean** → rgba(0, 119, 182, 0.39) - blue accent
- **sunset** → rgba(255, 140, 0, 0.40) - orange accent
- **forest** → rgba(34, 139, 34, 0.37) - green accent
- dst.

---

## 🚀 Deployment & Usage

### **Installation:**
```bash
# Plugin already in /plugins/latihan/latihan/
# Just run migrations
php artisan winter:up
```

### **Access:**
- **Backend List:** `/backend/latihan/latihan/teachers`
- **Create:** `/backend/latihan/latihan/teachers/create`
- **Edit:** `/backend/latihan/latihan/teachers/update/{id}`

### **Menu:**
- Backend sidebar → "Data Sekolah" → "Teachers"

---

## 📝 Summary

Website ini adalah **Teachers Management Dashboard** yang dibangun dengan Winter CMS. Fitur-fitur lengkap CRUD sudah terimplementasi dengan baik, form yang user-friendly, dan watermark branding yang prominent di seluruh halaman (frontend & backend).

Semua sudah **tested dan working**, dengan database migrations yang proper dan code yang clean. Plugin siap untuk production use! 🎉

---

**Last Updated:** 19 Februari 2026  
**Developer:** Armuzz Dev (Arya Musthofa)  
**License:** See LICENSE file in plugin directory
