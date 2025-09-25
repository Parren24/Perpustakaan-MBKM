<ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6">
    <li class="nav-item">
        <a class="nav-link {{ $pageData->activeMenu == 'agenda' ? 'active' : '' }}"
            href="{{ route('app.agenda.index') }}">Seluruh
            Agenda</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $pageData->activeMenu == 'agenda-kategori' ? 'active' : '' }}"
            href="{{ route('app.agenda.show', ['param1' => 'kategori']) }}">Kategori</a>
    </li>
</ul>
