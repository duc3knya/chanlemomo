<style>
    .iconx {
        color: #fff;
    }

    .iconx:hover {
        color: #fff;
        height: 47px;
    }
    .sidebar-menu li>a{
        color: #b8c7ce !important;
    }
    .sidebar-menu>li.header{
        color: #4b646f;
        background: #1a2226;;
    }

    .main-sidebar a, .main-header a, .main-sidebar .info{
        color: #fff;
    }
    .main-sidebar .form-control {
        color: #666;
        border-top-left-radius: 2px;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 2px;
        box-shadow: none;
        background-color: #374850;
        border: 1px solid transparent;
        height: 35px;
    }
    .main-sidebar .btn {
        color: #999;
        border-top-left-radius: 0;
        border-top-right-radius: 2px;
        border-bottom-right-radius: 2px;
        border-bottom-left-radius: 0;
        box-shadow: none;
        background-color: #374850;
        border: 1px solid transparent;
        height: 35px;
    }
    }
</style>

<header class="main-header">

    <!-- Logo -->
    <a href="{{ route('admin_home') }}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>A</b>LT</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>Admin</b>LTE</span>
    </a>

    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" data-toggle="push-menu" role="button" style="color: #fff;">
            <i class="fas fa-bars btn btn-lg iconx" style="margin-top: 3px;"></i>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="{{ asset('/image/avatar.png') }}"
                            class="user-image" alt="User Image">
                        <span class="hidden-xs">{{ Auth::user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="{{ asset('/image/avatar.png') }}"
                                class="img-circle" alt="User Image">

                            <p>
                                {{ Auth::user()->name }}
                                <small>( Quản trị viên )</small>
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="#" class="btn btn-default btn-flat">Profile</a>
                            </div>
                            <div class="pull-right">
                                <a href="{{ route('admin_logout') }}" class="btn btn-default btn-flat">Sign out</a>
                            </div>
                        </li>
                    </ul>
                </li>
                <!-- Control Sidebar Toggle Button -->
            </ul>
        </div>

    </nav>
</header>
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar" style="background-color: #222d32">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ asset('/image/avatar.png') }}"
                    class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ Auth::user()->name }}</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search...">
                <span class="input-group-btn">
                    <button type="submit" name="search" id="search-btn" class="btn btn-flat">
                        <i class="fa fa-search"></i>
                    </button>
                </span>
            </div>
        </form>
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MAIN NAVIGATION</li>
            <li><a href="{{ route('admin_home') }}"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a>
            </li>

            <li><a href="{{ route('admin.lichsubank.view') }}"><i class="fas fa-university"></i> <span>Lịch sử bank</span></a></li>

            
            <li class="treeview">
                <a href="#">
                    <i class="fas fa-history"></i>
                    <span>Lịch sử chơi</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin_lichsuchoi', 'chan-le') }}"><i class="fa fa-circle-o"></i> Trò chơi chẵn lẻ</a></li>
                    <li><a href="{{ route('admin_lichsuchoi', 'tai-xiu') }}"><i class="fa fa-circle-o"></i> Trò chơi tài xỉu</a></li>
                    <li><a href="{{ route('admin_lichsuchoi', 'chan-le-2') }}"><i class="fa fa-circle-o"></i> Trò chơi chẵn lẻ 2</a></li>
                    <li><a href="{{ route('admin_lichsuchoi', 'gap-3') }}"><i class="fa fa-circle-o"></i> Trò chơi gấp 3</a></li>
                    <li><a href="{{ route('admin_lichsuchoi', 'tong-3-so') }}"><i class="fa fa-circle-o"></i> Trò chơi tổng 3 số</a></li>
                    <li><a href="{{ route('admin_lichsuchoi', '1-phan-3') }}"><i class="fa fa-circle-o"></i> Trò chơi 1 phần 3</a></li>
                    <li><a href="{{ route('admin_lichsuchoi', 'no-hu') }}"><i class="fa fa-circle-o"></i> Trò chơi nổ hũ</a></li>
                </ul>
            </li>

            <li class="treeview">
                <a href="#">
                    <i class="fas fa-gamepad"></i> 
                    <span>Cấu hình trò chơi</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin_setting_chanle') }}"><i class="fa fa-circle-o"></i> Trò chơi chẵn lẻ</a></li>
                    <li><a href="{{ route('admin_setting_taixiu') }}"><i class="fa fa-circle-o"></i> Trò chơi tài xỉu</a></li>
                    <li><a href="{{ route('admin_setting_chanle2') }}"><i class="fa fa-circle-o"></i> Trò chơi chẵn lẻ 2</a></li>
                    <li><a href="{{ route('admin_setting_gap3') }}"><i class="fa fa-circle-o"></i> Trò chơi gấp 3</a></li>
                    <li><a href="{{ route('admin_setting_tong3so') }}"><i class="fa fa-circle-o"></i> Trò chơi tổng 3 số</a></li>
                    <li><a href="{{ route('admin_setting_1phan3') }}"><i class="fa fa-circle-o"></i> Trò chơi 1 phần 3</a></li>
                    <li><a href="{{ route('admin_setting_nohu') }}"><i class="fa fa-circle-o"></i> Trò chơi nổ hũ</a></li>
                    <li><a href="{{ route('admin_setting_diemdanh') }}"><i class="fa fa-circle-o"></i> Điểm danh nhận quà</a></li>
                    <li><a href="{{ route('admin_setting_attendance_date') }}"><i class="fa fa-circle-o"></i> Điểm danh ngày</a></li>
                </ul>
            </li>


            <li class="treeview">
                <a href="#">
                    <i class="fas fa-cogs"></i> 
                    <span>Thiết lập</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('admin_setting') }}"><i class="fa fa-circle-o"></i> Cài đặt website</a></li>
                    <li><a href="{{ route('admin_quanlysdt') }}"><i class="fa fa-circle-o"></i> Quản lý số điện thoại</a></li>
                    <li><a href="{{ route('admin_level_money') }}"><i class="fa fa-circle-o"></i> Quản lý hạn mức sđt</a></li>
                    <li><a href="{{ route('admin_setting_thuongtuan') }}"><i class="fa fa-circle-o"></i> Phần quà top tuần</a></li>
                    <li><a href="{{ route('admin_doi_mat_khau') }}"><i class="fa fa-circle-o"></i> Đổi mật khẩu</a></li>
                    <li><a href="{{ route('admin_config_message') }}"><i class="fa fa-circle-o"></i> Thiết lập trả thưởng</a></li>
                    {{-- <li><a href="{{ route('admin_update') }}"><i class="fa fa-circle-o"></i> Cập nhật phiên bản mới</a></li> --}}
                </ul>
            </li>

            <li><a href="{{ route('admin.update.view') }}"><i class="far fa-edit"></i> <span>Cập nhật</span></a></li>

        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            {{ $GetSetting->namepage }}
            <small>Version 2.0</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Admin</a></li>
            <li class="active">{{ $GetSetting->namepage }}</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
