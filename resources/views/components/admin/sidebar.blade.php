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

                <li @class(['active' => Route::is('admin.contact_messages.*')])>
                    <a href="{{ route('admin.contact_messages.index') }}">
                        <i class="fa fa-envelope"></i> Contact Messages
                    </a>
                </li>

                <li @class(['active' => Route::is('admin.books.*', 'admin.poems.*')])>
                    <a class="has-arrow" href="javascript:;">
                        <i class="fa fa-book hide-menu"></i> Works
                    </a>
                    <ul class="collapse">
                        <li><a @class(['active' => Route::is('admin.books.*')]) href="{{ route('admin.books.index') }}">Books</a></li>
                        <li><a @class(['active' => Route::is('admin.poems.*')]) href="{{ route('admin.poems.index') }}">Poems</a></li>
                    </ul>
                </li>

                <li @class(['active' => Route::is('admin.essays.*')])>
                    <a href="{{ route('admin.essays.index') }}">
                        <i class="fa fa-pencil-square-o"></i> Essays
                    </a>
                </li>

                <li @class(['active' => Route::is('admin.press_articles.*')])>
                    <a href="{{ route('admin.press_articles.index') }}">
                        <i class="fa fa-newspaper-o"></i> Press Articles
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</div>