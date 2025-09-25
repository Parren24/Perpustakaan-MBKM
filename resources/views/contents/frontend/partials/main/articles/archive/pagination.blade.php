    @php
        $data = data_get($content, 'articlesMeta');
        $currentPage = data_get($data, 'current_page');
        $lastPage = data_get($data, 'last_page');
        $pageShown = 3;
        $url = data_get($data, 'url');
    @endphp

    <div class="page-pagination wow fadeInUp" data-wow-delay="0.5s"
        style="visibility: visible; animation-delay: 0.5s; animation-name: fadeInUp;">
        <ul class="pagination">
            @if ($currentPage == 1)
                <li class="disabled"><span><i class="fa-solid fa-arrow-left-long"></i></span></li>
            @else
                <li><a href="{{ $url . '?page=' . ($currentPage - 1) }}"><i class="fa-solid fa-arrow-left-long"></i></a>
                </li>
            @endif

            @for ($i = $currentPage - 1; $i < $currentPage + $pageShown; $i++)
                @php
                    if ($i == 0) {
                        continue;
                    }
                @endphp
                @if ($i <= $lastPage)
                    @if ($i == $currentPage)
                        <li class="active"><a href="javascript:;">{{ $i }}</a></li>
                    @else
                        <li><a href="{{ $url . '?page=' . $i }}">{{ $i }}</a>
                        </li>
                    @endif
                @endif
            @endfor

            @if ($currentPage < $lastPage)
                <li><a href="{{ $url . '?page=' . ($currentPage + 1) }}"><i
                            class="fa-solid fa-arrow-right-long"></i></a></li>
            @else
                <li class="disabled"><span><i class="fa-solid fa-arrow-right-long"></i></span></li>
            @endif
        </ul>
    </div>
