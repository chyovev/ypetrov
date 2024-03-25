<form class="header-search pt-1">
    <input type="text" class="form-control" placeholder="{{ __('global.search') }}" name="search" value="{{ request()->query('search') }}" />
    <i class="fa fa-search pt-2" onclick="javascript: $(this).parent().submit()"></i>
</form>