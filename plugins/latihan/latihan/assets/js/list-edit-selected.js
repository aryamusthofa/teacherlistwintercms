(function(){
  // Inject Edit Selected button ke list toolbar
  function injectEditSelectedButton(){
    // Check if button already injected
    if (document.getElementById('latihan-edit-selected-btn')) return;

    // Find the toolbar with list controls
    var toolbar = document.querySelector('[data-control="list"] .toolbar') || 
                  document.querySelector('.list-toolbar') ||
                  document.querySelector('[role="toolbar"]');
    
    if (!toolbar) {
      console.warn('[Edit Selected] Toolbar not found');
      return;
    }

    // Find the button group
    var btnGroup = toolbar.querySelector('.button-strip') || 
                   toolbar.querySelector('.btn-toolbar');
    
    if (!btnGroup) {
      console.warn('[Edit Selected] Button group not found');
      return;
    }

    // Create Edit Selected button
    var editBtn = document.createElement('button');
    editBtn.id = 'latihan-edit-selected-btn';
    editBtn.type = 'button';
    editBtn.className = 'btn btn-secondary';
    editBtn.innerHTML = '<i class="icon-edit"></i> Edit Selected';
    
    // Click handler
    editBtn.addEventListener('click', function(e){
      e.preventDefault();
      var ids = [];
      document.querySelectorAll('input[data-field="checked"]:checked').forEach(function(ch){
        ids.push(ch.getAttribute('data-id'));
      });
      
      if (ids.length === 0) {
        alert('Pilih minimal satu record untuk diedit.');
        return;
      }
      
      // Navigate to edit first selected
      window.location.href = '/backend/latihan/latihan/teachers/update/' + ids[0];
    });

    // Insert button after delete button or at end
    var deleteBtn = toolbar.querySelector('[data-request="onDelete"]');
    if (deleteBtn && deleteBtn.parentNode) {
      deleteBtn.parentNode.insertBefore(editBtn, deleteBtn.nextSibling);
    } else {
      btnGroup.appendChild(editBtn);
    }

    console.log('[Edit Selected] Button injected successfully');
  }

  // Try injecting on DOMContentLoaded and also with retries
  document.addEventListener('DOMContentLoaded', function(){
    injectEditSelectedButton();
    // Retry after 500ms in case list loads async
    setTimeout(injectEditSelectedButton, 500);
    setTimeout(injectEditSelectedButton, 1000);
  });

  // Also try immediately if DOM already ready
  if (document.readyState === 'interactive' || document.readyState === 'complete') {
    injectEditSelectedButton();
  }
})();
