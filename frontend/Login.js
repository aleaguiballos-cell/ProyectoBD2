$(document).ready(function () {
  // Elimina el bloqueo del submit y el alert para permitir el funcionamiento normal del formulario
  $('form').on('submit', function(e) {
    // e.preventDefault();
    // alert('Intentando iniciar sesión...');
  });
});

document.addEventListener('DOMContentLoaded', function() {
  const btn = document.querySelector('.circle-btn');
  if (btn) {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      btn.classList.remove('animated');
      void btn.offsetWidth; // Reinicia la animación
      btn.classList.add('animated');
    });
  }

  const form = document.querySelector('.login-form form');
  if (form) {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      const formData = new FormData(form);
      fetch('login.php', {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        mostrarNotificacion(data.message, data.success);
        if (data.success) {
          setTimeout(() => {
            window.location.href = data.redirectUrl; //redireccion html 
          }, 1500);
        }
      })
      .catch(error => {
        mostrarNotificacion('Error de conexión', false);
      });
    });
  }

  const showRegisterBtn = document.getElementById('show-register');
  const registerForm = document.getElementById('register-form');
  if (showRegisterBtn && registerForm) {
    showRegisterBtn.addEventListener('click', function() {
      registerForm.style.display = registerForm.style.display === 'none' ? 'block' : 'none';
      showRegisterBtn.style.display = 'none';
    });
  }
});;
function mostrarNotificacion(mensaje, exito) {
  let noti = document.createElement('div');
  noti.className = 'noti-popup';
  noti.textContent = mensaje;
  noti.style.background = exito ? '#e74c3c' : '#e74c3c';
  document.body.appendChild(noti);
  setTimeout(() => {
    noti.classList.add('show');
  }, 50);
  setTimeout(() => {
    noti.classList.remove('show');
    setTimeout(() => noti.remove(), 400);
  }, 1200);
}
  const passwordInput = document.getElementById("passwordInput");
  const keyIcon = document.getElementById("KeyIcon");

  if (passwordInput && keyIcon) {
    passwordInput.addEventListener("input", function() {
      if (passwordInput.value.length > 0) {
        keyIcon.classList.add("hidden");   
      } else {
        keyIcon.classList.remove("hidden"); 
      }
    });
  }

