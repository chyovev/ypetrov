<x-admin.layout title="Contact Messages" route="admin.contact_messages.index">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="table-responsive m-t-40">
                        <table class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th class="text-center">ID</th>
                                    <th>Name</th>
                                    <th>E-mail</th>
                                    <th>Message</th>
                                    <th>Country</th>
                                    <th>Created at</th>
                                    <th width="160" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($messages as $message)
                                <tr @class(['font-weight-bold' => $message->isUnread()])>
                                    <td class="text-center">{{ $message->id }}</td>
                                    <td>{{ Str::of($message->name)->limit(30) }}</td>
                                    <td><a href="mailto:{{ $message->email }}">{{ Str::of($message->email)->limit(30) }}</a></td>
                                    <td>{{ Str::of($message->message)->stripTags()->limit(40) }}</td>
                                    <td class="text-center">
                                        <x-admin.flag :countryCode="$message->visitor->country_code" />
                                    </td>
                                    <td>{{ $message->created_at->format('d.m.Y. @ H:i:s') }}</td>
                                    <td>
                                        <a href="{{ route('admin.contact_messages.show',    ['contact_message' => $message]) }}" class="btn btn-success btn-sm"><i class="fa fa-eye"></i> View</a>
                                        <a href="{{ route('admin.contact_messages.destroy', ['contact_message' => $message]) }}" class="btn btn-danger  btn-sm confirm-delete"><i class="fa fa-trash"></i> Delete</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="m-t-30">{{ $messages->links() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-admin.layout>