<!doctype html>
<html lang="en">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--favicon-->
	<link rel="icon" href="{{ url('/images/logos/default_logo.png') }}" type="image/png" />
	<!--plugins-->
	<link href="{{ asset('assets/plugins/simplebar/css/simplebar.css')}}" rel="stylesheet" />
	<link href="{{ asset('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css')}}" rel="stylesheet" />
	<link href="{{ asset('assets/plugins/metismenu/css/metisMenu.min.css')}}" rel="stylesheet" />
	<!-- loader-->
	<link href="{{ asset('assets/css/pace.min.css')}}" rel="stylesheet" />
	<script src="{{ asset('assets/js/pace.min.js')}}"></script>
	<!-- Bootstrap CSS -->
	<link href="{{ asset('assets/css/bootstrap.min.css')}}" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
	<link href="{{ asset('assets/css/app.css')}}" rel="stylesheet">
	<link href="{{ asset('assets/css/icons.css')}}" rel="stylesheet">
	<!-- Theme Style CSS -->
	<link rel="stylesheet" href="{{ asset('assets/css/dark-theme.css')}}" />
	<link rel="stylesheet" href="{{ asset('assets/css/semi-dark.css')}}" />
	<link rel="stylesheet" href="{{ asset('assets/css/header-colors.css')}}" />
	<!-- fontawesome icon -->
	<link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome/css/fontawesome-all.min.css') }}">
	<link href="https://kit-pro.fontawesome.com/releases/v6.4.2/css/pro.min.css" rel="stylesheet">

	<!-- datatable -->
	<link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet">

	<title>Admin system</title>
</head>
<style>
	.topbar-nav .metismenu a.active {
		color: #ffffff !important;
		text-decoration: none !important;
		background: #8833ff !important;
	}

	.topbar-bg {
	    background-image: url("{{ url('assets/images/bg-themes/9.png') }}");
	    background-size: cover;
	    background-position: center;
	    background-repeat: no-repeat;
	    height: 100px;
	}

</style>

<body>
	<!--wrapper-->
	<div class="wrapper">
		<!--start header wrapper-->
		<div class="header-wrapper">
			<!--start header -->
			<header>
				<div class="topbar d-flex align-items-center">
					<nav class="navbar navbar-expand topbar-bg">
						<div class="topbar-logo-header">
							<div class="">
								<img src="{{ url('/images/logos/test_logo.png') }}" class="logo-icon" alt="logo icon" style="border-radius: 50%; object-fit: cover;background: #fff;">
							</div>
							<!-- <div class="">
								<h4 class="logo-text" style="color: #383333;">ERS - Management</h4>
							</div> -->
						</div>
						<div class="mobile-toggle-menu"><i class='bx bx-menu'></i></div>
						<div class="top-menu-left d-none d-lg-block ps-3">
							<ul class="nav">
								<b>
									ชื่อ.. (หน่วยงาน)
								</b>
							</ul>
						</div>
						<div class="search-bar flex-grow-1">
							<div class="position-relative search-bar-box">
								<input type="text" class="form-control search-control" placeholder="Type to search..."> <span class="position-absolute top-50 search-show translate-middle-y"><i class='bx bx-search'></i></span>
								<span class="position-absolute top-50 search-close translate-middle-y"><i class='bx bx-x'></i></span>
							</div>
						</div>
						<div class="top-menu ms-auto">
							<ul class="navbar-nav align-items-center d-none">
								<li class="nav-item mobile-search-icon">
									<a class="nav-link" href="#"> <i class='bx bx-search'></i>
									</a>
								</li>
								<li class="nav-item dropdown dropdown-large">
									<a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"> <i class='bx bx-category'></i>
									</a>
									<div class="dropdown-menu dropdown-menu-end">
										<div class="row row-cols-3 g-3 p-3">
											<div class="col text-center">
												<div class="app-box mx-auto bg-gradient-cosmic text-white"><i class='bx bx-group'></i>
												</div>
												<div class="app-title">Teams</div>
											</div>
											<div class="col text-center">
												<div class="app-box mx-auto bg-gradient-burning text-white"><i class='bx bx-atom'></i>
												</div>
												<div class="app-title">Projects</div>
											</div>
											<div class="col text-center">
												<div class="app-box mx-auto bg-gradient-lush text-white"><i class='bx bx-shield'></i>
												</div>
												<div class="app-title">Tasks</div>
											</div>
											<div class="col text-center">
												<div class="app-box mx-auto bg-gradient-kyoto text-dark"><i class='bx bx-notification'></i>
												</div>
												<div class="app-title">Feeds</div>
											</div>
											<div class="col text-center">
												<div class="app-box mx-auto bg-gradient-blues text-dark"><i class='bx bx-file'></i>
												</div>
												<div class="app-title">Files</div>
											</div>
											<div class="col text-center">
												<div class="app-box mx-auto bg-gradient-moonlit text-white"><i class='bx bx-filter-alt'></i>
												</div>
												<div class="app-title">Alerts</div>
											</div>
										</div>
									</div>
								</li>
								<li class="nav-item dropdown dropdown-large">
									<a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"> <span class="alert-count">7</span>
										<i class='bx bx-bell'></i>
									</a>
									<div class="dropdown-menu dropdown-menu-end">
										<a href="javascript:;">
											<div class="msg-header">
												<p class="msg-header-title">Notifications</p>
												<p class="msg-header-clear ms-auto">Marks all as read</p>
											</div>
										</a>
										<div class="header-notifications-list">
											<a class="dropdown-item" href="javascript:;">
												<div class="d-flex align-items-center">
													<div class="notify bg-light-primary text-primary"><i class="bx bx-group"></i>
													</div>
													<div class="flex-grow-1">
														<h6 class="msg-name">New Customers<span class="msg-time float-end">14 Sec
																ago</span></h6>
														<p class="msg-info">5 new user registered</p>
													</div>
												</div>
											</a>
											<a class="dropdown-item" href="javascript:;">
												<div class="d-flex align-items-center">
													<div class="notify bg-light-danger text-danger"><i class="bx bx-cart-alt"></i>
													</div>
													<div class="flex-grow-1">
														<h6 class="msg-name">New Orders <span class="msg-time float-end">2 min
																ago</span></h6>
														<p class="msg-info">You have recived new orders</p>
													</div>
												</div>
											</a>
											<a class="dropdown-item" href="javascript:;">
												<div class="d-flex align-items-center">
													<div class="notify bg-light-success text-success"><i class="bx bx-file"></i>
													</div>
													<div class="flex-grow-1">
														<h6 class="msg-name">24 PDF File<span class="msg-time float-end">19 min
																ago</span></h6>
														<p class="msg-info">The pdf files generated</p>
													</div>
												</div>
											</a>
											<a class="dropdown-item" href="javascript:;">
												<div class="d-flex align-items-center">
													<div class="notify bg-light-warning text-warning"><i class="bx bx-send"></i>
													</div>
													<div class="flex-grow-1">
														<h6 class="msg-name">Time Response <span class="msg-time float-end">28 min
																ago</span></h6>
														<p class="msg-info">5.1 min avarage time response</p>
													</div>
												</div>
											</a>
											<a class="dropdown-item" href="javascript:;">
												<div class="d-flex align-items-center">
													<div class="notify bg-light-info text-info"><i class="bx bx-home-circle"></i>
													</div>
													<div class="flex-grow-1">
														<h6 class="msg-name">New Product Approved <span
																class="msg-time float-end">2 hrs ago</span></h6>
														<p class="msg-info">Your new product has approved</p>
													</div>
												</div>
											</a>
											<a class="dropdown-item" href="javascript:;">
												<div class="d-flex align-items-center">
													<div class="notify bg-light-danger text-danger"><i class="bx bx-message-detail"></i>
													</div>
													<div class="flex-grow-1">
														<h6 class="msg-name">New Comments <span class="msg-time float-end">4 hrs
																ago</span></h6>
														<p class="msg-info">New customer comments recived</p>
													</div>
												</div>
											</a>
											<a class="dropdown-item" href="javascript:;">
												<div class="d-flex align-items-center">
													<div class="notify bg-light-success text-success"><i class='bx bx-check-square'></i>
													</div>
													<div class="flex-grow-1">
														<h6 class="msg-name">Your item is shipped <span class="msg-time float-end">5 hrs
																ago</span></h6>
														<p class="msg-info">Successfully shipped your item</p>
													</div>
												</div>
											</a>
											<a class="dropdown-item" href="javascript:;">
												<div class="d-flex align-items-center">
													<div class="notify bg-light-primary text-primary"><i class='bx bx-user-pin'></i>
													</div>
													<div class="flex-grow-1">
														<h6 class="msg-name">New 24 authors<span class="msg-time float-end">1 day
																ago</span></h6>
														<p class="msg-info">24 new authors joined last week</p>
													</div>
												</div>
											</a>
											<a class="dropdown-item" href="javascript:;">
												<div class="d-flex align-items-center">
													<div class="notify bg-light-warning text-warning"><i class='bx bx-door-open'></i>
													</div>
													<div class="flex-grow-1">
														<h6 class="msg-name">Defense Alerts <span class="msg-time float-end">2 weeks
																ago</span></h6>
														<p class="msg-info">45% less alerts last 4 weeks</p>
													</div>
												</div>
											</a>
										</div>
										<a href="javascript:;">
											<div class="text-center msg-footer">View All Notifications</div>
										</a>
									</div>
								</li>
								<li class="nav-item dropdown dropdown-large">
									<a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"> <span class="alert-count">8</span>
										<i class='bx bx-comment'></i>
									</a>
									<div class="dropdown-menu dropdown-menu-end">
										<a href="javascript:;">
											<div class="msg-header">
												<p class="msg-header-title">Messages</p>
												<p class="msg-header-clear ms-auto">Marks all as read</p>
											</div>
										</a>
										<div class="header-message-list">
											<!--  -->
										</div>
										<a href="javascript:;">
											<div class="text-center msg-footer">View All Messages</div>
										</a>
									</div>
								</li>

							</ul>
						</div>
						<div class="user-box dropdown">
							<a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
								<div class="user-info ps-3">
									<p class="user-name mb-0 has-arrow">
										{{ Auth::user()->name }}
									</p>
									<p class="designattion mb-0">
										@if(Auth::user()->role == "admin_zone")
											แอดมิน
										@else
											เจ้าหน้าที่
										@endif
									</p>
								</div>
							</a>
							<ul class="dropdown-menu dropdown-menu-end">
								<li>
									<a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
										<i class='bx bx-log-out-circle'></i><span>Logout</span>
										<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
											@csrf
										</form>
									</a>
								</li>
							</ul>
						</div>
					</nav>
				</div>
			</header>
			<!--end header -->
			<!--navigation-->
			<div class="nav-container">
				<div class="mobile-topbar-header">
					<!--  -->
				</div>
				<nav class="topbar-nav">
					<ul class="metismenu" id="menu">
						<li class="main-submenu">
							<a href="{{ url('/control_and_command') }}" class="sub-menu">
								<div class="parent-icon">
									<i class="fa-solid fa-user-headset"></i>
								</div>
								<div class="menu-title">ควบคุมและสั่งการ</div>
							</a>
						</li>

						<li class="main-submenu">
							<a class="has-arrow" href="javascript:;">
								<div class="parent-icon">
									<i class="fa-solid fa-user-plus"></i>
								</div>
								<div class="menu-title">หน่วยปฏิบัติการ</div>
							</a>
							<ul>
								<li>
									<a href="{{ url('/list_unit') }}" class="sub-menu">
										<i class="bx bx-right-arrow-alt"></i>รายชื่อหน่วยปฏิบัติการ
									</a>
								</li>
								<li>
									<a href="{{ url('/add_unit') }}" class="sub-menu">
										<i class="bx bx-right-arrow-alt"></i>เพิ่มหน่วยปฏิบัติการ
									</a>
								</li>
								<li>
									<a href="{{ url('/add_unit_forward') }}" class="sub-menu">
										<i class="bx bx-right-arrow-alt"></i>เพิ่มหน่วยงานสำหรับส่งต่อ
									</a>
								</li>
							</ul>
						</li>

						<li class="main-submenu">
							<a class="has-arrow" href="javascript:;">
								<div class="parent-icon">
									<i class="fa-solid fa-chart-pie"></i>
								</div>
								<div class="menu-title">วิเคราะห์ข้อมูล</div>
							</a>
							<ul>
								<li>
									<a href="{{ url('/analyze_officer') }}" class="sub-menu">
										<i class="bx bx-right-arrow-alt"></i>ข้อมูลเจ้าหน้าที่ศูนย์สั่งการ
									</a>
								</li>
								<li>
									<a href="{{ url('/analyze_sos') }}" class="sub-menu">
										<i class="bx bx-right-arrow-alt"></i>ข้อมูลการขอความช่วยเหลือ
									</a>
								</li>
							</ul>
						</li>

						<li class="main-submenu">
							<a class="has-arrow" href="javascript:;">
								<div class="parent-icon">
									<i class="fas fa-user-shield"></i>
								</div>
								<div class="menu-title">การจัดการ</div>
							</a>
							<ul>
								<li>
									<a href="{{ url('/manage_member_command') }}" class="sub-menu">
										<i class="bx bx-right-arrow-alt"></i>สมาชิกศูนย์สั่งการ
									</a>
								</li>
								<li>
									<a href="{{ url('/manage_map_command') }}" class="sub-menu">
										<i class="bx bx-right-arrow-alt"></i>แผนที่
									</a>
								</li>
								<li>
									<a href="{{ url('/manage_organization') }}" class="sub-menu">
										<i class="bx bx-right-arrow-alt"></i>จัดการองค์กร
									</a>
								</li>
							</ul>
						</li>
						<li class="main-submenu">
							<a class="has-arrow" href="javascript:;">
								<div class="parent-icon">
									<i class="fas fa-braille"></i>
								</div>
								<div class="menu-title">อื่นๆ</div>
							</a>
							<ul>
								<li>
									<a href="{{ url('/other_how_to_user') }}" class="sub-menu">
										<i class="bx bx-right-arrow-alt"></i>วิธีใช้งาน
									</a>
								</li>
								<li>
									<a href="{{ url('/other_video') }}" class="sub-menu">
										<i class="bx bx-right-arrow-alt"></i>วิดีโอสอนการใช้งาน
									</a>
								</li>
								<li>
									<a href="{{ url('/other_poster') }}" class="sub-menu">
										<i class="bx bx-right-arrow-alt"></i>สื่อประชาสัมพันธ์
									</a>
								</li>
								<li>
									<a href="{{ url('/other_report') }}" class="sub-menu">
										<i class="bx bx-right-arrow-alt"></i>แจ้งปัญหาการใช้งาน
									</a>
								</li>
								@if(Auth::check())
									@if( Auth::user()->id == '1' || Auth::user()->id == '64' || Auth::user()->id == '11003429'  )
										<li>
											<a href="{{ url('/create_zone_partner') }}" class="sub-menu text-danger">
												<i class="bx bx-right-arrow-alt"></i>Create Partner (For Dev)
											</a>
										</li>
									@endif
								@endif
							</ul>
						</li>
					</ul>
				</nav>
			</div>
			<!--end navigation-->
		</div>
		<!--end header wrapper-->

		<!-- CONTENT -->
		<div class="page-wrapper">
			<div class="page-content">
				<div class="container-fluid">
					@yield('content')
				</div>
			</div>
		</div>
		<!-- EDN CONTENT -->

		<!--end page wrapper -->
		<!--start overlay-->
		<div class="overlay toggle-icon"></div>
		<!--end overlay-->
		<!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
	</div>
	<!--end wrapper-->
	<!-- Bootstrap JS -->

	<script src="{{ asset('assets/js/bootstrap.bundle.min.js')}}"></script>
	<!--plugins-->
	<script src="{{ asset('assets/js/jquery.min.js')}}"></script>
	<script src="{{ asset('assets/plugins/simplebar/js/simplebar.min.js')}}"></script>
	<script src="{{ asset('assets/plugins/metismenu/js/metisMenu.min.js')}}"></script>
	<script src="{{ asset('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js')}}"></script>
	<!--app JS-->
	<script src="{{ asset('assets/js/app.js')}}"></script>

	<!-- datatable -->
	<script src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
	<script src="{{ asset('assets/plugins/datatable/js/dataTables.bootstrap5.min.js')}}"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	<script>
		document.addEventListener('DOMContentLoaded', (event) => {
			check_submenu()
		});

		function check_submenu() {
			let menu = $('.sub-menu');
			var winlocation = window.location.href.split('?')[0]; // Get current URL without query params
			let activeClassAdded = false; // Flag to check if active class is added

			for (i = 0; i < menu.length; i++) {
				let submenu = menu[i];
				let menuLink = submenu.getAttribute("href");

				// Check if current URL matches the menu link
				if (winlocation.includes(menuLink)) {
					submenu.classList.add("active"); // Add active class to the submenu
					submenu.closest('.main-submenu').querySelector('a').classList.add("active"); // Add active class to parent menu
					activeClassAdded = true;
				}
			}

		}
	</script>
	</script>
</body>

</html>