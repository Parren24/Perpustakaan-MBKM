<x-frontend.hero :slides="data_get($heroData, 'slides', [])" :hero_data="$heroData" :site_identity="$siteIdentity">
    <x-slot name="subtitle">
        {!! data_get($heroData, 'content.subtitle', 'Selamat Datang di Politeknik Caltex Riau') !!}
    </x-slot>
</x-frontend.hero>
