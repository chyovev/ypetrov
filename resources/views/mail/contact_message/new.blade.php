<x-mail::message>
    <div style="text-align: center"><strong>Ново контактно съобщение</strong></div><br />
    <strong>Дата</strong>: {{ $contactMessage->created_at->translatedFormat('l, d.m.Y г., H:i ч.') }}<br />
    <strong>Линк към CMS</strong>: <a href="{{ route('admin.contact_messages.show', ['contact_message' => $contactMessage]) }}" target="_blank">{{ route('admin.contact_messages.show', ['contact_message' => $contactMessage]) }}</a><br />
    <br />
    <strong>От</strong>: {{ $contactMessage->name }}<br />
    <strong>E-mail</strong>: {{ $contactMessage->email }}<br />
    <strong>Съобщение</strong>: {!! nl2br(e($contactMessage->message)) !!}
</x-mail::message>
