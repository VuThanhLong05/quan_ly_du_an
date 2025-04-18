 <!-- Main Sidebar Container -->
 <aside class="main-sidebar sidebar-dark-primary elevation-4">
   <!-- Brand Logo -->
   <a href="../../index3.html" class="brand-link">
     <img src="./assets/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
     <span class="brand-text font-weight-light">Website gấu bông</span>
   </a>

   <!-- Sidebar -->
   <div class="sidebar">
     <!-- Sidebar user (optional) -->
     <div class="user-panel mt-3 pb-3 mb-3 d-flex">
       <div class="image">
         <img src="./assets/dist/img/avatar56.jpg" class="img-circle elevation-2" alt="User Image">
       </div>
       <div class="info">
         <a href="#" class="d-block">Nhóm 2</a>
       </div>
     </div>

     <!-- Sidebar Menu -->
     <nav class="mt-2">
       <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
         <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
         <li class="nav-item">
           <a href="<?= BASE_URL_ADMIN . '?act=danh-muc' ?>" class="nav-link">
             <i class="nav-icon fas fa-chart-pie"></i>
             <p>
               Danh mục
             </p>
           </a>
         </li>

         <li class="nav-item">
           <a href="<?= BASE_URL_ADMIN . '?act=san-pham' ?>" class="nav-link">
             <i class="nav-icon fas fa-paw"></i>
             <p>
               Sản phẩm
             </p>
           </a>
         </li>

       </ul>
     </nav>
     <!-- /.sidebar-menu -->
   </div>
   <!-- /.sidebar -->
 </aside>