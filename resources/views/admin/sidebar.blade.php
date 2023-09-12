
  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/admin" class="brand-link">
      <a class="btn btn-sm btn-primary" href="{{ route('admin/logout') }}" class="brand-text font-weight-light">Đăng xuất</a>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="/template/admin/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ Session::get('email')}}</a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

          @hasrole(['admin', 'author'])
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Sản phẩm
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/admin/product/add" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Thêm sản phẩm</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/product/list" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Danh sách sản phẩm</p>
                </a>
              </li>
            </ul>
          </li>
          @endhasrole
          @hasrole(['admin', 'author'])
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Danh mục
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/admin/menu/add" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Thêm danh mục</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/menu/list" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Danh sách danh mục</p>
                </a>
              </li>
            </ul>
          </li>
          @endhasrole
          @hasrole(['admin', 'author'])
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-images"></i>
              <p>
                Slider
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/admin/slider/add" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Thêm Slider</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/slider/list" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Danh sách Slider</p>
                </a>
              </li>
            </ul>
          </li>
          @endhasrole

          @hasrole(['admin', 'user'])
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-images"></i>
              <p>
                Đơn hàng
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/admin/customers" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Danh sách đơn hàng</p>
                </a>
              </li>
            </ul>
          </li>
          @endhasrole
          
          @hasrole(['admin', 'author'])
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-images"></i>
              <p>
                Chi nhánh
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="/admin/store/add" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Thêm chi nhánh</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/store" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Danh sách chi nhánh</p>
                </a>
              </li>
            </ul>
          </li>
          @endhasrole
          
          @hasrole(['admin', 'author'])
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-images"></i>
              <p>
                Nhân viên
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
            <li class="nav-item">
              <li class="nav-item">
                <a href="/admin/users/list" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Danh sách nhân viên</p>
                </a>
              </li>
            </ul>
          </li>
          @endhasrole          
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>