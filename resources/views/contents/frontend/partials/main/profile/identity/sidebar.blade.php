<div class="service-sidebar">
    <div class="service-catagery-list wow fadeInUp">
        <h3>Daftar Isi</h3>
        <ul>
            @foreach (data_get($content, 'identity_guide', []) as $item)
                <li><a href="#{{ data_get($item, 'id') }}">{{ data_get($item, 'title') }}</a></li>
            @endforeach
        </ul>
    </div>
</div>
