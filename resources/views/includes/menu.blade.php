  <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="{{asset('adminlte/dist/img/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">SYSTEM CHAINAT</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{asset('adminlte/dist/img/user2-160x160.jpg')}}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">CNT HOSPITAL</a>
        </div>
      </div>
      
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item{{ request()->is('systempersonal/frontend/person/setting/*') ? ' menu-open' : ''}}">
            <a href="#" class="nav-link{{ request()->is('systempersonal/frontend/person/setting/*') ? ' active' : ''}}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                ตั้งค่าข้อมูลพื้นฐาน
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{url('/systempersonal/frontend/person/setting/user')}}" class="nav-link{{ request()->is('systempersonal/frontend/person/setting/user') ? ' active' : ''}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>ตั้งค่าผู้ใช้งานระบบ</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('/systempersonal/frontend/person/setting/education')}}" class="nav-link{{ request()->is('systempersonal/frontend/person/setting/education') ? ' active' : ''}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>ตั้งค่าการศึกษา</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('/systempersonal/frontend/person/setting/persontype')}}" class="nav-link{{ request()->is('systempersonal/frontend/person/setting/persontype') ? ' active' : ''}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>ตั้งค่าประเภทการจ้างงาน</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('/systempersonal/frontend/person/setting/persontypehelp')}}" class="nav-link{{ request()->is('systempersonal/frontend/person/setting/persontypehelp') ? ' active' : ''}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>ตั้งค่าประเภทการปฏิบัติงาน</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('/systempersonal/frontend/person/setting/personposition')}}" class="nav-link{{ request()->is('systempersonal/frontend/person/setting/personposition') ? ' active' : ''}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>ตั้งค่าตำแหน่ง</p>
                </a>
              </li>
              
            </ul>
          </li>




          <li class="nav-item{{ request()->is('systempersonal/frontend/person/settingleave/*') ? ' menu-open' : ''}}">
            <a href="#" class="nav-link{{ request()->is('systempersonal/frontend/person/settingleave/*') ? ' active' : ''}}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                ตั้งค่าข้อมูลการลา
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{url('/systempersonal/frontend/person/settingleave/persontypeleave')}}" class="nav-link{{ request()->is('systempersonal/frontend/person/settingleave/persontypeleave') ? ' active' : ''}}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>ตั้งค่าประเภทการลา</p>
                  </a>
                </li>
              </ul>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->