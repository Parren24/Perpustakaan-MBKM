@component('mail::message')
    # Pesan Baru dari Formulir Kontak

    Anda menerima pesan baru dari formulir kontak situs web.

    **Nama:** {{ $name }}
    **Email:** {{ $email }}
    **Subjek:** {{ $subject }}
    **Pesan:**
    {{ $message }}

    @component('mail::button', ['url' => 'mailto:' . $email])
        Balas Pesan Ini
    @endcomponent

    Terima kasih,<br>
    {{ config('app.name') }}
@endcomponent
