<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <div class="d-flex sidebar-profile">
                <div class="sidebar-profile-image">
                    <img src="{{ asset('assets') }}/images/faces/face29.png" alt="image">
                    <span class="sidebar-status-indicator"></span>
                </div>
                <div class="sidebar-profile-name">
                    <p class="sidebar-name">
                        {{ auth()->user()->name }}
                    </p>
                    <p class="sidebar-designation">
                        Active
                    </p>
                </div>
            </div>
        </li>
        @can('Dashboard')
            <li class="nav-item">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i class="typcn typcn-device-desktop menu-icon"></i>
                    <span class="menu-title">Dashboard </span>
                </a>
            </li>
        @endcan
        @canany(['Role Index', 'Permission Index', 'User Index'])
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
                    <i class="typcn typcn-briefcase menu-icon"></i>
                    <span class="menu-title">Manajemen User</span>
                    <i class="typcn typcn-chevron-right menu-arrow"></i>
                </a>
                <div class="collapse" id="ui-basic">
                    <ul class="nav flex-column sub-menu">
                        @can('Role Index')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('roles.index') }}">Role</a>
                            </li>
                        @endcan
                        @can('Permission Index')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('permissions.index') }}">Permission</a>
                            </li>
                        @endcan
                        @can('User Index')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('users.index') }}">User</a>
                            </li>
                        @endcan
                    </ul>
                </div>
            </li>
        @endcanany
        @canany(['Department Index', 'Unit Index'])
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#master-ui" aria-expanded="false"
                    aria-controls="master-ui">
                    <i class="typcn typcn-briefcase menu-icon"></i>
                    <span class="menu-title">Master Data</span>
                    <i class="typcn typcn-chevron-right menu-arrow"></i>
                </a>
                <div class="collapse" id="master-ui">
                    <ul class="nav flex-column sub-menu">
                        @can('Department Index')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('departments.index') }}">Department</a>
                            </li>
                        @endcan
                        @can('Unit Index')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('units.index') }}">Unit</a>
                            </li>
                        @endcan
                        @can('Category Index')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('categories.index') }}">Category</a>
                            </li>
                        @endcan
                    </ul>
                </div>
            </li>
        @endcanany
    </ul>
</nav>
