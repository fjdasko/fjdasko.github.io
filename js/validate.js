// validate.js - jednoduchá validácia a odoslanie formulára pomocou fetch
document.addEventListener('DOMContentLoaded',()=>{
  const form = document.getElementById('contact-form');
  const result = document.getElementById('form-result');
  if(!form) return;

  function showMessage(msg,ok){
    result.textContent = msg;
    result.style.color = ok ? 'green' : 'crimson';
  }

  form.addEventListener('submit',async(e)=>{
    e.preventDefault();
    result.textContent = '';

    // basic client-side checks
    const name = form.full_name.value.trim();
    const email = form.email.value.trim();
    const subject = form.subject.value.trim();
    const message = form.message.value.trim();
    const consent = form.consent_terms.checked;
    if(!name || !email || !subject || !message || !consent){
      showMessage('Prosím vyplňte povinné polia a súhlas.', false);
      return;
    }
    if(form.file_upload.files.length>0){
      const f = form.file_upload.files[0];
      if(f.size > 2*1024*1024){ showMessage('Súbor je príliš veľký (max 2 MB).', false); return; }
    }

    // submit via fetch - send FormData so files are included
    const fd = new FormData(form);
    try{
      const resp = await fetch(form.action, {method:'POST', body:fd});
      const data = await resp.json();
      if(data && data.success){
        showMessage('Formulár úspešne odoslaný. Presmerovanie...', true);
        // redirect to result page showing stored data
        setTimeout(()=>{
          if(data.id) window.location.href = 'php/result.php?id='+encodeURIComponent(data.id);
          else window.location.href = 'php/result.php';
        },800);
      } else {
        showMessage(data.error || 'Odoslanie zlyhalo.', false);
      }
    }catch(err){
      showMessage('Chyba pri odosielaní. Skúste neskôr.', false);
    }
  });
});

