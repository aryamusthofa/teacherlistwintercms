// ── Theme Switcher ──
function applyTheme(name) {
  document.body.setAttribute('data-theme', name);
  document.documentElement.setAttribute('data-theme', name);
  localStorage.setItem('armuzz-theme', name);

  document.querySelectorAll('.theme-swatch').forEach(function(el) {
    if (!el.classList.contains('lang-option') && !el.classList.contains('tz-option')) {
      el.classList.toggle('active', el.dataset.theme === name);
    }
  });
}

// ── Sidebar Collapse ──
function toggleSidebar() {
  var sidebar = document.getElementById('appSidebar');
  var icon = document.getElementById('sidebarToggleIcon');
  sidebar.classList.toggle('collapsed');

  if (sidebar.classList.contains('collapsed')) {
    icon.classList.remove('bi-chevron-bar-left');
    icon.classList.add('bi-chevron-bar-right');
    localStorage.setItem('armuzz-sidebar', 'collapsed');
  } else {
    icon.classList.remove('bi-chevron-bar-right');
    icon.classList.add('bi-chevron-bar-left');
    localStorage.setItem('armuzz-sidebar', 'expanded');
  }
}

// ── Mobile Sidebar ──
function openMobileSidebar() {
  document.getElementById('appSidebar').classList.add('mobile-open');
  document.getElementById('mobileOverlay').classList.add('show');
}

function closeMobileSidebar() {
  document.getElementById('appSidebar').classList.remove('mobile-open');
  document.getElementById('mobileOverlay').classList.remove('show');
}

// ── Timezone Conversion ──
var SERVER_OFFSET = 7; // server = UTC+7

function applyTimezone(offset) {
  localStorage.setItem('armuzz-tz', offset);
  var userOffset = parseFloat(offset);

  var lbl = document.getElementById('tzLabel');
  if (lbl) {
    var sign = userOffset >= 0 ? '+' : '';
    var display = Number.isInteger(userOffset) ? userOffset.toString() : userOffset.toString();
    lbl.textContent = 'UTC' + sign + display;
  }

  document.querySelectorAll('.tz-option').forEach(function(el) {
    el.classList.toggle('active', el.dataset.tz === offset);
  });

  document.querySelectorAll('.tz-date').forEach(function(el) {
    var raw = el.getAttribute('data-utc');
    if (!raw || raw === '') { el.textContent = '-'; return; }

    var parts = raw.replace(/[-:\/]/g, ' ').split(' ').map(Number);
    if (parts.length < 5) return;
    
    var d = new Date(Date.UTC(parts[0], parts[1]-1, parts[2], parts[3]-SERVER_OFFSET, parts[4], parts[5]||0));
    var userMs = d.getTime() + (userOffset * 3600000);
    var userDate = new Date(userMs);

    var dd = String(userDate.getUTCDate()).padStart(2,'0');
    var mm = String(userDate.getUTCMonth()+1).padStart(2,'0');
    var yyyy = userDate.getUTCFullYear();
    var hh = String(userDate.getUTCHours()).padStart(2,'0');
    var mi = String(userDate.getUTCMinutes()).padStart(2,'0');
    el.textContent = dd + '/' + mm + '/' + yyyy + ' ' + hh + ':' + mi;
  });
}

// ── Initialize on Load ──
document.addEventListener('DOMContentLoaded', function() {
  var savedTheme = localStorage.getItem('armuzz-theme') || 'default';
  applyTheme(savedTheme);

  var sidebarState = localStorage.getItem('armuzz-sidebar');
  if (sidebarState === 'collapsed' && window.innerWidth >= 992) {
    document.getElementById('appSidebar').classList.add('collapsed');
    var icon = document.getElementById('sidebarToggleIcon');
    if(icon) {
      icon.classList.remove('bi-chevron-bar-left');
      icon.classList.add('bi-chevron-bar-right');
    }
  }

  var savedTz = localStorage.getItem('armuzz-tz') || '+7';
  applyTimezone(savedTz);

  setTimeout(function() {
    document.documentElement.classList.remove('preload-transitions');
  }, 50);

  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      document.getElementById('themePicker')?.classList.remove('open');
      document.getElementById('langPicker')?.classList.remove('open');
      document.getElementById('tzPicker')?.classList.remove('open');
      closeMobileSidebar();
    }
  });
});