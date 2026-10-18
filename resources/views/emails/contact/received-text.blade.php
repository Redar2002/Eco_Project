NOUVEAU MESSAGE — {{ $contactMessage->subject_label }}

De      : {{ $contactMessage->name }} <{{ $contactMessage->email }}>
@if ($contactMessage->phone)
Tél.    : {{ $contactMessage->phone }}
@endif
Reçu le : {{ $contactMessage->created_at->translatedFormat('d F Y à H:i') }}

{{ $contactMessage->message }}

---
Répondez simplement à cet e-mail pour écrire au client.
Back-office : {{ $adminUrl }}
