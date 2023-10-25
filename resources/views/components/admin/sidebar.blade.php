<div class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li class="nav-devider"></li>
                <li class="nav-label">Home</li>
                <li @class(['active' => Route::is('admin.home')])>
                    <a href="{{ route('admin.home') }}">
                        <i class="fa fa-tachometer"></i> Dashboard
                    </a>
                </li>

                <li @class(['active' => Route::is('admin.users.*')])>
                    <a href="{{ route('admin.users.index') }}">
                        <i class="fa fa-users"></i> Users
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>