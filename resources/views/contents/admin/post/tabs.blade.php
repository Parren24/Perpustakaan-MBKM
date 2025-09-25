<ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6">
    @foreach ($pageData->dataKategori as $value)
        <li class="nav-item">
            <a class="nav-link {{ $pageData->activeMenu == 'post-kat-' . $value['kode'] ? 'active' : '' }}"
                href="{{ route('app.post.show', ['param1' => $value['kode']]) }}"><i
                    class="ki-outline ki-pin me-2"></i>Seluruh
                {{ $value['text'] }}</a>
        </li>
    @endforeach
    <li class="nav-item">
        <a class="nav-link {{ $pageData->activeMenu == 'post-kategori' ? 'active' : '' }}"
            href="{{ route('app.post.show', ['param1' => 'kategori']) }}">Kategori</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $pageData->activeMenu == 'post-label' ? 'active' : '' }}"
            href="{{ route('app.post.show', ['param1' => 'label']) }}">Label</a>
    </li>
</ul>
