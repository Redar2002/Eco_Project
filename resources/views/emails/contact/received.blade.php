<x-mail-layout :subject="'Nouveau message de '.$contactMessage->name" :preheader="\Illuminate\Support\Str::limit($contactMessage->message, 90)">
    <tr>
        <td class="px" style="padding:36px 40px 0;">
            <p style="margin:0;font-size:12px;font-weight:bold;letter-spacing:2px;text-transform:uppercase;color:#D97706;">Nouveau message · {{ $contactMessage->subject_label }}</p>
            <h1 class="h1" style="margin:10px 0 0;font-size:24px;line-height:30px;font-weight:bold;color:#0F172A;">{{ $contactMessage->name }} vous a écrit</h1>
            <p style="margin:12px 0 0;font-size:14px;line-height:22px;color:#475569;">
                <a href="mailto:{{ $contactMessage->email }}" style="color:#1E3A8A;">{{ $contactMessage->email }}</a>
                @if ($contactMessage->phone) · {{ $contactMessage->phone }}@endif
                <br><span style="color:#64748B;">Reçu le {{ $contactMessage->created_at->translatedFormat('d F Y à H:i') }}</span>
            </p>
        </td>
    </tr>
    <tr>
        <td class="px" style="padding:24px 40px 0;">
            <div style="background:#F8FAFC;border-left:3px solid #1E3A8A;border-radius:8px;padding:18px 20px;font-size:15px;line-height:24px;color:#0F172A;">{!! nl2br(e($contactMessage->message)) !!}</div>
        </td>
    </tr>
    <tr>
        <td class="px" style="padding:8px 40px 40px;text-align:center;">
            @include('emails.partials.button', ['url' => 'mailto:'.$contactMessage->email.'?subject='.rawurlencode('Re: '.$contactMessage->subject_label), 'label' => 'Répondre au client'])
            <p style="margin:16px 0 0;font-size:13px;color:#64748B;">Ou consultez-le dans le <a href="{{ $adminUrl }}" style="color:#1E3A8A;">back-office</a>.</p>
        </td>
    </tr>
</x-mail-layout>
