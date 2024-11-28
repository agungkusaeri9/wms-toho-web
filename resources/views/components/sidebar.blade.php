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
            <li class="nav-item @if (request()->is('/')) active @endif">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i class="typcn typcn-home menu-icon"></i>
                    <span class="menu-title">Dashboard </span>
                </a>
            </li>
        @endcan
        @canany(['Stock In Index', 'Stock Out'])
            <li class="nav-item @if (request()->is('stock-ins') || request()->is('stock-outs')) active @endif">
                <a class="nav-link" data-toggle="collapse" href="#stock-ui" aria-expanded="false" aria-controls="stock-ui">
                    <i class="typcn typcn-archive  menu-icon"></i>
                    <span class="menu-title">Stock</span>
                    <i class="typcn typcn-chevron-right menu-arrow"></i>
                </a>
                <div class="collapse @if (request()->is('stock-ins') || request()->is('stock-outs')) show @endif" id="stock-ui">
                    <ul class="nav flex-column sub-menu">
                        @can('Stock In Index')
                            <li class="nav-item @if (request()->is('stock-ins')) active @endif">
                                <a class="nav-link" href="{{ route('stock-ins.index') }}">In</a>
                            </li>
                        @endcan
                        @can('Stock Out Index')
                            <li class="nav-item @if (request()->is('stock-outs')) active @endif">
                                <a class="nav-link" href="{{ route('stock-outs.index') }}">Out</a>
                            </li>
                        @endcan
                    </ul>
                </div>
            </li>
        @endcanany
        @canany(['Category Index', 'Product Index'])
            <li class="nav-item @if (request()->is('products') || request()->is('categories')) active @endif">
                <a class="nav-link" data-toggle="collapse" href="#product-ui" aria-expanded="false"
                    aria-controls="product-ui">
                    <i class="typcn typcn-shopping-bag  menu-icon"></i>
                    <span class="menu-title">Product</span>
                    <i class="typcn typcn-chevron-right menu-arrow"></i>
                </a>
                <div class="collapse  @if (request()->is('products') || request()->is('categories')) show @endif" id="product-ui">
                    <ul class="nav flex-column sub-menu">
                        @can('Category Index')
                            <li class="nav-item @if (request()->is('categories')) active @endif">
                                <a class="nav-link" href="{{ route('categories.index') }}">Category</a>
                            </li>
                        @endcan
                        @can('Product Index')
                            <li class="nav-item @if (request()->is('products')) active @endif">
                                <a class="nav-link" href="{{ route('products.index') }}">Product</a>
                            </li>
                        @endcan
                    </ul>
                </div>
            </li>
        @endcanany
        @canany(['Report Stock In Index', 'Report Stock Out'])
            <li class="nav-item @if (request()->is('report/stock-ins') || request()->is('report/stock-outs')) active @endif">
                <a class="nav-link" data-toggle="collapse" href="#report-stock-ui" aria-expanded="false"
                    aria-controls="report-stock-ui">
                    <i class="typcn typcn-document  menu-icon"></i>
                    <span class="menu-title">Report</span>
                    <i class="typcn typcn-chevron-right menu-arrow"></i>
                </a>
                <div class="collapse @if (request()->is('report/stock-ins') || request()->is('report/stock-outs')) show @endif" id="report-stock-ui">
                    <ul class="nav flex-column sub-menu">
                        @can('Report Product Index')
                            <li class="nav-item @if (request()->is('report/product')) active @endif">
                                <a class="nav-link" href="{{ route('products.report.index') }}">Product</a>
                            </li>
                        @endcan
                        @can('Report Stock In Index')
                            <li class="nav-item @if (request()->is('report/stock-ins')) active @endif">
                                <a class="nav-link" href="{{ route('stock-ins.report.index') }}">Stock In</a>
                            </li>
                        @endcan
                        @can('Report Stock Out Index')
                            <li class="nav-item @if (request()->is('report/stock-outs')) active @endif">
                                <a class="nav-link" href="{{ route('stock-outs.report.index') }}">Stock Out</a>
                            </li>
                        @endcan
                    </ul>
                </div>
            </li>
        @endcanany
        @canany(['Role Index', 'Permission Index', 'User Index'])
            <li class="nav-item  @if (request()->is('users') || request()->is('roles') || request()->is('permissions')) active @endif">
                <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
                    <i class="typcn typcn-group menu-icon"></i>
                    <span class="menu-title">Manajemen User</span>
                    <i class="typcn typcn-chevron-right menu-arrow"></i>
                </a>
                <div class="collapse  @if (request()->is('users') || request()->is('roles') || request()->is('permissions')) show @endif" id="ui-basic">
                    <ul class="nav flex-column sub-menu">
                        @can('Role Index')
                            <li class="nav-item @if (request()->is('roles')) active @endif">
                                <a class="nav-link" href="{{ route('roles.index') }}">Role</a>
                            </li>
                        @endcan
                        @can('Permission Index')
                            <li class="nav-item @if (request()->is('permissions')) active @endif">
                                <a class="nav-link" href="{{ route('permissions.index') }}">Permission</a>
                            </li>
                        @endcan
                        @can('User Index')
                            <li class="nav-item @if (request()->is('users')) active @endif">
                                <a class="nav-link" href="{{ route('users.index') }}">User</a>
                            </li>
                        @endcan
                    </ul>
                </div>
            </li>
        @endcanany
        @canany(['Department Index', 'Unit Index', 'Area Index'])
            <li class="nav-item @if (request()->is('departments') || request()->is('units') || request()->is('areas')) active @endif">
                <a class="nav-link" data-toggle="collapse" href="#master-ui" aria-expanded="false"
                    aria-controls="master-ui">
                    <i class="typcn typcn-database  menu-icon"></i>
                    <span class="menu-title">Master Data</span>
                    <i class="typcn typcn-chevron-right menu-arrow"></i>
                </a>
                <div class="collapse @if (request()->is('departments') || request()->is('units') || request()->is('areas')) show @endif" id="master-ui">
                    <ul class="nav flex-column sub-menu">
                        @can('Department Index')
                            <li class="nav-item @if (request()->is('departments')) active @endif">
                                <a class="nav-link" href="{{ route('departments.index') }}">Department</a>
                            </li>
                        @endcan
                        @can('Unit Index')
                            <li class="nav-item @if (request()->is('units')) active @endif">
                                <a class="nav-link" href="{{ route('units.index') }}">Unit</a>
                            </li>
                        @endcan
                        @can('Area Index')
                            <li class="nav-item @if (request()->is('areas')) active @endif">
                                <a class="nav-link" href="{{ route('areas.index') }}">Area</a>
                            </li>
                        @endcan
                        @can('Supplier Index')
                            <li class="nav-item @if (request()->is('suppliers')) active @endif">
                                <a class="nav-link" href="{{ route('suppliers.index') }}">Supplier</a>
                            </li>
                        @endcan
                    </ul>
                </div>
            </li>
        @endcanany
    </ul>
</nav>
