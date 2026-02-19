(function(){
  // Inject Edit Selected button ke list toolbar jika ada list container
  document.addEventListener('DOMContentLoaded', function(){
    setTimeout(function(){
      var listToolbar = document.querySelector('[data-role="list-toolbar"]') || document.querySelector('.list-toolbar');
      if (!listToolbar) return; // No toolbar found

      // Check if Edit Selected button already exists
      if (document.getElementById('latihan-edit-selected-btn')) return;

      // Find the toolbar container (usually .btn-toolbar or .btn-group)
      var btnGroup = listToolbar.querySelector('.btn-group') || listToolbar.querySelector('.btn-toolbar');
      if (!btnGroup) return;

      // Create Edit Selected button
      var editBtn = document.createElement('button');
      editBtn.id = 'latihan-edit-selected-btn';
      editBtn.type = 'button';
      editBtn.className = 'btn btn-secondary';
      editBtn.innerHTML = '<i class="icon-edit"></i> Edit Selected';
      
      // Insert after the first button group
      var firstBtnGroup = listToolbar.querySelector('.btn-group');
      if (firstBtnGroup && firstBtnGroup.nextSibling) {
        firstBtnGroup.parentNode.insertBefore(editBtn, firstBtnGroup.nextSibling);
      } else {
        btnGroup.appendChild(editBtn);
      }

      // Wire click handler
      editBtn.addEventListener('click', function(e){
        e.preventDefault();
        var ids = [];
        document.querySelectorAll('input[name="checked[]"]:checked').forEach(function(ch){
          ids.push(ch.value);
        });
        if (ids.length === 0) {
          alert('Pilih minimal satu record untuk diedit.');
          return;
        }
        // Open edit form for first selected id
        window.location.href = '/backend/latihan/latihan/teachers/update/' + ids[0];
      });
    }, 500); // Wait for DOM to settle
  });
})();
