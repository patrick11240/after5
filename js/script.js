document.addEventListener('DOMContentLoaded', () => {
   // Toggle menu and navbar visibility
   let menu = document.querySelector('#menu-btn');
   let navbar = document.querySelector('.header .navbar');

   if (menu && navbar) {
      menu.onclick = () => {
         menu.classList.toggle('fa-times');
         navbar.classList.toggle('active');
      };
   }

   // Close menu and navbar when scrolling
   window.onscroll = () => {
      if (menu && navbar) {
         menu.classList.remove('fa-times');
         navbar.classList.remove('active');
      }
   };

   // Close edit form container and redirect to admin.php
   let closeEditBtn = document.querySelector('#close-edit');
   if (closeEditBtn) {
      closeEditBtn.onclick = () => {
         document.querySelector('.edit-form-container').style.display = 'none';
         window.location.href = 'admin.php';
      };
   }
});
