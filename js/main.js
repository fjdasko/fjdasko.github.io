// main.js - drobné skripty: mobilné menu, CSRF token fetch
document.addEventListener('DOMContentLoaded',function(){
  const toggle = document.getElementById('nav-toggle');
  const nav = document.getElementById('main-nav');
  if(toggle){
    toggle.addEventListener('click',()=>{
      const expanded = toggle.getAttribute('aria-expanded') === 'true';
      toggle.setAttribute('aria-expanded', String(!expanded));
      if(nav.style.display === 'block') nav.style.display = '';
      else nav.style.display = 'block';
    });
  }

  // fetch CSRF token for forms (if any)
  fetch('php/get_csrf.php').then(r=>r.json()).then(data=>{
    if(data && data.csrf_token){
      const el = document.getElementById('csrf_token');
      if(el) el.value = data.csrf_token;
    }
  }).catch(()=>{/* ignore errors */});
});

