(function(){
  document.addEventListener('DOMContentLoaded', function(){
    if (document.getElementById('latihan-backend-watermark')) return;

    var wm = document.createElement('div');
    wm.id = 'latihan-backend-watermark';
    wm.innerHTML = '\n      <div class="latihan-wm-content">\n        <div class="latihan-wm-name">Arya Musthofa</div>\n        <div class="latihan-wm-sub">Asal Jateng — Armuzz Dev</div>\n        <div class="latihan-wm-role">Software Engineering & AI Engineering</div>\n        <div class="latihan-wm-social">@galeri_armus</div>\n        <div class="latihan-wm-copy">©2026 Hak Cipta Dilindungi Pencipta</div>\n      </div>\n    ';

    document.body.appendChild(wm);
  });
})();
