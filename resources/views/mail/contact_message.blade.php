<x-mail::message>
    <div style="text-align: center"><strong>Ново контактно съобщение</strong></div><br />
    <strong>Дата</strong>: {{ $message->created_at->translatedFormat('l, d.m.Y г., H:i ч.') }}<br />
    <strong>Линк към CMS</strong>: <a href="{{ route('admin.contact_messages.show', ['contact_message' => $message]) }}" target="_blank">{{ route('admin.contact_messages.show', ['contact_message' => $message]) }}</a><br />
    <br />
    <strong>От</strong>: {{ $message->name }}<br />
    <strong>E-mail</strong>: {{ $message->email }}<br />
    <strong>Съобщение</strong>: {!! nl2br(e($message->message)) !!}
</x-mail::message>
