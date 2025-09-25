<ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6">
    <li class="nav-item">
        <a class="nav-link {{ $pageData->activeMenu == 'testi' ? 'active' : '' }}"
            href="{{ route('app.testi.index') }}">Seluruh
            Testimoni</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $pageData->activeMenu == 'testi-kategori' ? 'active' : '' }}"
            href="{{ route('app.testi.show', ['param1' => 'kategori']) }}">Kategori</a>
    </li>
</ul>
