<x-admin.layout route="admin.users.index">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-title">
                    <h3 class="text-primary">
                        <em class="fa fa-list"></em> {{ __('global.users') }}
                        <x-admin.total-results :items="$users" />
                        <div class="dt-buttons float-right ml-2">
                            <a class="btn btn-success" href="{{ route('admin.users.create') }}"><i class="fa fa-plus"></i> {{ __('global.create') }}</a>
                        </div>
                        <x-admin.search />
                    </h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive m-t-40">
                        <table class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>{{ __('global.name') }}</th>
                                    <th>{{ __('global.email') }}</th>
                                    <th>{{ __('global.created_at') }}</th>
                                    <th width="200" class="text-center">{{ __('global.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                <tr>
                                    <td class="text-center">{{ (($users->currentPage() - 1) * $users->perPage()) + $loop->iteration }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                                    <td>{{ $user->created_at->translatedFormat('d.m.Y. @ H:i:s') }}</td>
                                    <td>
                                        <a href="{{ route('admin.users.edit',    ['user' => $user]) }}" class="btn btn-info   btn-sm"><i class="fa fa-pencil"></i> {{ __('global.edit') }}</a>
                                        @can('delete', $user)
                                        <a href="{{ route('admin.users.destroy', ['user' => $user]) }}" class="btn btn-danger btn-sm confirm-delete"><i class="fa fa-trash"></i>  {{ __('global.delete') }}</a>
                                        @endcan
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <x-admin.pagination :data="$users" />
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-admin.layout>