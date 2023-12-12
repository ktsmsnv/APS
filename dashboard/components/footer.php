   <!-- JQuery UI -->
   <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
   <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
   <!-- icons -->
   <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
   <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
   <!-- chart js -->
   <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
   <!-- Data Table JS -->
   <script src='https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js'></script>
   <script src='https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js'></script>
   <script src='https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js'></script>
   <!-- Font Awesome -->
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
   <!-- Swiper JS -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.0.7/css/swiper.min.css" />
   <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.0.7/js/swiper.min.js"></script>

   <script>
    $(document).ready(function() {
     // меню бургер toggle
       let toggle = document.querySelector('.toggle');
       let navigation = document.querySelector('.navigation');
       let main = document.querySelector('.main');
       toggle.addEventListener('click', () => {
           navigation.classList.toggle('active');
           main.classList.toggle('active');
       });

     // добавить класс hovered выбранному list item в навигационном меню
       let list = document.querySelectorAll('.navigation li');

       function activeLink(item) {
           list.forEach((item) =>
               item.classList.remove('hovered'));
           item.classList.add('hovered');
       }
       list.forEach((item) =>
           item.addEventListener('mouseover', () => activeLink(item)));

     // сладйер на главной
       //  массив ["График 1", "График 2", ..., "График 10"]
       var menu = Array.from({ length: 10 }, (_, i) => `График ${i + 1}`);
       var menu = ['График 1', 'График 2', 'График 3']
       var mySwiper = new Swiper('.swiper-container', {
           // loop: true,
           spaceBetween: 30,
           centeredSlides: true,
           allowTouchMove:false, 
           //     autoplay: {
           //     delay: 2500,
           //     disableOnInteraction: false,
           // },
           pagination: {
               el: '.swiper-pagination',
               clickable: true,
               renderBullet: function(index, className) {
                   return '<span class="' + className + '">' + (menu[index]) + '</span>';
               },
           },
           navigation: {
               nextEl: '.swiper-button-next',
               prevEl: '.swiper-button-prev',
           },
       })
     });
   </script>