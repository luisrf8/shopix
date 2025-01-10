document.addEventListener("DOMContentLoaded", function () {
    const toggleButton = document.getElementById('toggleSidenav');
    const sidenav = document.getElementById('sidenav-main');
    const body = document.getElementById('d-body');
  
    toggleButton.addEventListener('click', function () {
      console.log("hola");
      // Verificar si la clase 'd-none' está presente
      if (sidenav.classList.contains('d-none')) {
        sidenav.classList.add('sidenav');
        sidenav.classList.remove('d-none');
      } else {
        sidenav.classList.remove('sidenav');
        sidenav.classList.add('d-none');
      }
    });
  });
  