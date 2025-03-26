<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <!-- User Profile-->
                <li>
                    <div class="user-profile dropdown m-t-20">
                        <div class="user-pic">
                            <img src="{{ url('assets/images/users/user.jpg') }}" alt="users"
                                class="rounded-circle img-fluid" />
                        </div>
                        <div class="user-content hide-menu m-t-10">
                            <h5 class="m-b-10 user-name font-medium">
                                {{ request()->session()->get('nama') }} | {{ request()->session()->get('kd_divisi') }}
                            </h5>
                            <a href="{{ url('auth/logout') }}" title="Logout" class="btn btn-circle btn-sm">
                                <i class="ti-power-off"></i>
                            </a>
                        </div>
                    </div>
                </li>

                @php
                $id_role = request()->session()->get('id_role');
                $menu = DB::select("SELECT to_number(substr(id_menu, 2, 5)) id, urutan_menu as urutan,
                id_menu, nama_menu, url, icon FROM v_menu_role WHERE rec_stat_menu='A' AND id_role='" . $id_role . "'
                ORDER BY urutan");
                @endphp

                @foreach ($menu as $row)
                @php
                $hit_submenu = collect(DB::select("SELECT count(id_submenu) jml FROM v_submenu_menu WHERE
                rec_stat_submenu='A' AND id_menu='" . $row->id_menu . "'"))->first();
                @endphp
                <li class="sidebar-item">
                    @if ($hit_submenu->jml == 0)
                    <a class='sidebar-link waves-effect waves-dark sidebar-link' href='{{ url($row->url) }}'
                        aria-expanded='false'>
                        @else
                        <a class='sidebar-link has-arrow waves-effect waves-dark' href='javascript:void(0)'
                            aria-expanded='false'>
                            @endif
                            <i class="{{ $row->icon }}"></i>
                            <span class="hide-menu">{{ $row->nama_menu }}</span>
                        </a>
                        <?php echo $hit_submenu->jml == 0 ? null : '<ul aria-expanded="false" class="collapse first-level">' ?>
                        @php
                        $submenu = DB::select("SELECT urutan_submenu, nama_submenu, url, icon FROM v_submenu_menu WHERE
                        rec_stat_submenu='A' AND id_menu='" . $row->id_menu . "' order by urutan_submenu");
                        @endphp
                        @foreach ($submenu as $row)
                <li class="sidebar-item">
                    <a href="{{ url($row->url) }}" class="sidebar-link">
                        <i class="{{ $row->icon }}" style="visibility: visible;"></i>
                        <span class="hide-menu">{{ $row->nama_submenu }}</span>
                    </a>
                </li>
                @endforeach
                <?php echo $hit_submenu->jml == 0 ? '' : '</ul>' ?>
                @endforeach
                </li>
            </ul>
        </nav>
        <!-- End Sidebar navigation -->

        <!-- Logo Pelindo di Bawah -->
        <div class="user-profile position-relative">
            <div class="text-center">
                <img src="{{ url('assets/images/akhlak.png') }}" alt="akhlak" id="akhlakLogo" style="transition: all 0.3s ease;">
            </div>
        </div>

    </div>
    <!-- End Sidebar scroll-->
</aside>

<style>
    #akhlakLogo {
        width: 120px; /* atau sesuaikan dengan ukuran normal yang diinginkan */
        height: auto;
        transition: all 0.3s ease;
    }

    .mini-sidebar #akhlakLogo {
        width: 45px; /* atau sesuaikan dengan ukuran kecil yang diinginkan */
    }
</style>
