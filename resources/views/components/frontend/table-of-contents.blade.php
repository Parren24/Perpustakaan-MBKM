@props(['items', 'title' => 'Daftar Isi'])

<div class="service-sidebar">
    <div class="service-catagery-list wow fadeInUp">
        <h3>{{ $title }}</h3>
        <ul>
            @foreach ($items as $item)
                <li><a href="#{{ $item['id'] }}">{{ $item['title'] }}</a></li>
            @endforeach
        </ul>
    </div>
</div>
