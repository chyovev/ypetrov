<div class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li class="nav-devider"></li>
                <li class="nav-label">{{ __('global.home') }}</li>
                <li @class(['active' => Route::is('admin.home')])>
                    <a href="{{ route('admin.home') }}">
                        <i class="fa fa-tachometer"></i> {{ __('global.dashboard') }}
                    </a>
                </li>

                <li @class(['active' => Route::is('admin.users.*')])>
                    <a href="{{ route('admin.users.index') }}">
                        <i class="fa fa-users"></i> {{ __('global.users') }}
                    </a>
                </li>

                <li @class(['active' => Route::is('admin.contact_messages.*')])>
                    <a href="{{ route('admin.contact_messages.index') }}">
                        <i class="fa fa-envelope"></i> {{ __('global.contact_messages') }}
                    </a>
                </li>

                <li @class(['active' => Route::is('admin.books.*', 'admin.poems.*')])>
                    <a class="has-arrow" href="javascript:;">
                        <i class="fa fa-book hide-menu"></i> {{ __('global.works') }}
                    </a>
                    <ul class="collapse">
                        <li><a @class(['active' => Route::is('admin.books.*')]) href="{{ route('admin.books.index') }}">{{ __('global.books') }}</a></li>
                        <li><a @class(['active' => Route::is('admin.poems.*')]) href="{{ route('admin.poems.index') }}">{{ __('global.poems') }}</a></li>
                    </ul>
                </li>

                <li @class(['active' => Route::is('admin.essays.*')])>
                    <a href="{{ route('admin.essays.index') }}">
                        <i class="fa fa-pencil-square-o"></i> {{ __('global.essays') }}
                    </a>
                </li>

                <li @class(['active' => Route::is('admin.press_articles.*')])>
                    <a href="{{ route('admin.press_articles.index') }}">
                        <i class="fa fa-newspaper-o"></i> {{ __('global.press_articles') }}
                    </a>
                </li>

                <li @class(['active' => Route::is('admin.videos.*')])>
                    <a href="{{ route('admin.videos.index') }}">
                        <i class="fa fa-video-camera"></i> {{ __('global.videos') }}
                    </a>
                </li>

                <li @class(['active' => Route::is('admin.gallery_images.*')])>
                    <a href="{{ route('admin.gallery_images.index') }}">
                        <i class="fa fa-picture-o"></i> {{ __('global.gallery') }}
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</div>