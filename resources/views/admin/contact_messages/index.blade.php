<x-admin.layout route="admin.contact_messages.index">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-title">
                    <h3 class="text-primary">
                        <em class="fa fa-list"></em> {{ __('global.contact_messages') }}
                        <x-admin.total-results :items="$messages" />
                        <x-admin.search />
                    </h3>
                </div>
                <div class="card-body">

                    <div class="table-responsive m-t-40">
                        <table class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th class="text-center">ID</th>
                                    <th>{{ __('global.name') }}</th>
                                    <th>{{ __('global.email') }}</th>
                                    <th>{{ __('global.message') }}</th>
                                    <th>{{ __('global.country') }}</th>
                                    <th>{{ __('global.created_at') }}</th>
                                    <th width="190" class="text-center">{{ __('global.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($messages as $message)
                                <tr @class(['font-weight-bold' => $message->isUnread()])>
                                    <td class="text-center">{{ $message->id }}</td>
                                    <td>{{ Str::of($message->name)->limit(30) }}</td>
                                    <td>
                                        @if ($message->email)
                                            <a href="mailto:{{ $message->email }}">{{ Str::of($message->email)->limit(30) }}</a>
                                        @else
                                            &ndash;
                                        @endif
                                    </td>
                                    <td>{{ Str::of($message->message)->stripTags()->limit(40) }}</td>
                                    <td class="text-center">
                                        <x-admin.flag :countryCode="$message->visitor->country_code" />
                                    </td>
                                    <td>{{ $message->created_at->translatedFormat('d.m.Y. @ H:i:s') }}</td>
                                    <td>
                                        <a href="{{ route('admin.contact_messages.show',    ['contact_message' => $message]) }}" class="btn btn-success btn-sm"><i class="fa fa-eye"></i> {{ __('global.view') }}</a>
                                        <a href="{{ route('admin.contact_messages.destroy', ['contact_message' => $message]) }}" class="btn btn-danger  btn-sm confirm-delete"><i class="fa fa-trash"></i> {{ __('global.delete') }}</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <x-admin.pagination :data="$messages" />
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-admin.layout>