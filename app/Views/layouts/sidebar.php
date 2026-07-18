 <!-- Sidebar -->
 <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

   <!-- Sidebar - Brand -->
   <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('index') ?>">
     <div class="sidebar-brand-icon rotate-n-15">
       <i class="fas fa-laugh-wink"></i>
     </div>
     <div class="sidebar-brand-text mx-3">SpendTrack</div>
   </a>

   <!-- Divider -->
   <hr class="sidebar-divider my-0">

   <!-- Nav Item - Dashboard -->
   <li class="nav-item active">
     <a class="nav-link" href="<?= base_url('index') ?>">
       <i class="fas fa-fw fa-tachometer-alt"></i>
       <span>Dashboard</span></a>
   </li>

   <!-- Divider -->
   <hr class="sidebar-divider">

   <!-- Nav Item - Pages Collapse Menu -->
   <li class="nav-item">
     <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
       aria-expanded="true" aria-controls="collapseTwo">
       <i class="fas fa-fw fa-money-bill"></i>
       <span>Expenses</span>
     </a>
     <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
       <div class="bg-white py-2 collapse-inner rounded">
         <h6 class="collapse-header">Custom Components:</h6>
         <a class="collapse-item" href="<?= base_url('expenses/index') ?>">Expense List</a>
         <a class="collapse-item" href="<?= base_url('expenses/create') ?>">Add Expenses</a>
       </div>
     </div>
   </li>

   <!-- Divider -->
   <hr class="sidebar-divider">

   <!-- Nav Item - Reports -->
   <li class="nav-item active">
     <a class="nav-link" href="<?= base_url('reports/index') ?>">
       <i class="fas fa-fw fa-tachometer-alt"></i>
       <span>Reports</span></a>
   </li>

 </ul>
 <!-- End of Sidebar -->