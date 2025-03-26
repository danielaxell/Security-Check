<header class="topbar">
    <nav class="navbar top-navbar navbar-expand-md navbar-dark">
        <div class="navbar-header">
            <!-- This is for the sidebar toggle which is visible on mobile only -->
            <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)">
                <i class="ti-menu ti-close"></i>
            </a>
            <!-- ============================================================== -->
            <!-- Logo -->
            <!-- ============================================================== -->
            <a class="navbar-brand" href="{{ url('') }}">
                <!-- Logo icon -->
                <b class="logo-icon">
                    <img src="{{ url('assets/images/logoaja.png') }}" alt="homepage" height=32 />
                </b>
                <!--End Logo icon -->
                <!-- Logo text -->
                <span class="logo-text">
                    <span style="color: white; font-weight: bold;">Security Check</span>
                </span>
            </a>
            <!-- ============================================================== -->
            <!-- End Logo -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Toggle which is visible on mobile only -->
            <!-- ============================================================== -->
            <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)"
                data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                aria-expanded="false" aria-label="Toggle navigation">
                <i class="ti-more"></i>
            </a>
        </div>
        
        <!-- ============================================================== -->
        <!-- End Logo -->
        <!-- ============================================================== -->
        <div class="navbar-collapse collapse" id="navbarSupportedContent">
            <!-- ============================================================== -->
            <!-- toggle and nav items -->
            <!-- ============================================================== -->
            <ul class="navbar-nav float-left mr-auto">
                <li class="nav-item d-none d-md-block">
                    <a class="nav-link sidebartoggler waves-effect waves-light" href="javascript:void(0)"
                        data-sidebartype="mini-sidebar">
                        <i class="sl-icon-menu font-20"></i>
                    </a>
                </li>
                <!-- ============================================================== -->
                <!-- Notif -->
                <!-- ============================================================== -->
                <!-- <li class="nav-item dropdown"> -->
                <?php
                // $jobuserapps = $db->query("SELECT count(id_pekerjaan) jml FROM v_pekerjaan_userapproval WHERE nipp='" . session()->get('nipp') . "'")->getRowArray();
                // if ($jobuserapps['jml'] > 0) { 
                ?>
                <!-- <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="ti-bell font-20"></i>
                        <span class="badge badge-pill badge-secondary"><? //= $jobuserapps['jml']; 
                                                                        ?></span>
                    </a>
                    <div class="dropdown-menu mailbox animated bounceInDown">
                        <span class="with-arrow">
                            <span class="bg-danger"></span>
                        </span>
                        <ul class="list-style-none">
                            <li>
                                <div class="drop-title bg-danger text-white">
                                    <h4 class="m-b-0 m-t-5"><? //= $jobuserapps['jml'] 
                                                            ?> New</h4>
                                    <span class="font-light">Notifikasi</span>
                                </div>
                            </li>
                            <li>
                                <div class="message-center notifications"> -->
                <!-- Message -->
                <?php
                // $jobuserapps = $db->query("SELECT id_pekerjaan, nama_terminal, nama_pekerjaan, selisih_hr FROM v_pekerjaan_userapproval WHERE nipp='" . session()->get('nipp') . "'")->getResultArray();
                // foreach ($jobuserapps as $row) { 
                ?>
                <!-- <a href="javascript:void(0)" class="message-item">
                                            <span class="btn btn-info btn-circle">
                                                <i class="fa fa-link"></i>
                                            </span>
                                            <div class="mail-contnet">
                                                <h5 class="message-title"><? //= $row['nama_terminal'] 
                                                                            ?></h5>
                                                <span class="mail-desc"><? //= $row['nama_pekerjaan'] 
                                                                        ?></span>
                                                <span class="time"><? //= $row['selisih_hr'] 
                                                                    ?> days</span>
                                            </div>
                                        </a> -->
                <?php //} 
                ?>
                
                <!-- </div>
                </li>
                <li>
                    <a class="nav-link text-center m-b-5" href="javascript:void(0);">
                        <strong>Cek Semua Notifikasi</strong>
                        <i class="fa fa-angle-right"></i>
                    </a>
                </li>
            </ul>
        </div> -->
        <nav class="navbar d-flex justify-content-center align-items-center" style="min-height: 60px;">
    <img src="{{ url('assets/images/pelindo.png') }}" alt="center logo" height="40" />
</nav>


                <?php //} 
                ?>
                <!-- </li> -->
                <!-- ============================================================== -->
                <!-- End Notif -->
                <!-- ============================================================== -->
            </ul>
            <!-- ============================================================== -->
            <!-- Right side toggle and nav items -->
            <!-- ============================================================== -->
            <ul class="navbar-nav float-right">
                <!-- ============================================================== -->
                <!-- User profile and search -->
                <!-- ============================================================== -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark pro-pic" href=""
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src="{{ url('assets/images/users/user.jpg') }}" alt="user" class="rounded-circle"
                            width="31">
                    </a>
                    <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY">
                        <span class="with-arrow">
                            <span class="bg-danger"></span>
                        </span>
                        <div class="d-flex no-block align-items-center p-15 bg-danger text-white m-b-10">
                            <div class="m-l-10">
                                <h4 class="m-b-0">{{ request()->session()->get('nama') }}</h4>
                                <p class=" m-b-0">{{ request()->session()->get('nama_role') . ' | ' .
                                    request()->session()->get('nama_terminal') }}</p>
                            </div>
                        </div>
                        @php
                        $id_role = request()->session()->get('id_role');
                        $sessions = collect(DB::select("SELECT count(id_session) jml FROM tb_session WHERE id_role='" .
                        $id_role . "'"))->first();
                        @endphp
                        @if ($sessions->jml > 0)
                        <a class="dropdown-item" id="btn-session" href="javascript:void(0)" data-toggle="modal"
                            data-target="#sessionModal">
                            <i class="ti-exchange-vertical m-r-5 m-l-5"></i> Change Session</a>
                        <div class="dropdown-divider"></div>
                        @endif
                        <a class="dropdown-item" id="btn-password" href="javascript:void(0)" data-toggle="modal"
                            data-target="#passwordModal">
                            <i class="ti-unlock m-r-5 m-l-5"></i> Change Password</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ url('auth/logout') }}">
                            <i class="fa fa-power-off m-r-5 m-l-5"></i> Logout</a>
                    </div>
                </li>
                <!-- ============================================================== -->
                <!-- User profile and search -->
                <!-- ============================================================== -->
            </ul>
        </div>
        
    </nav>
</header>


<!-- Modal Change Password -->
<div class="modal fade" id="passwordModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Ubah Password Anda</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {{ view('auth.password') }}
        </div>
    </div>
</div>

<!-- Modal Change Session -->
<div class="modal fade" id="sessionModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Ubah Session Anda</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {{ view('auth.session') }}
        </div>
    </div>
</div>