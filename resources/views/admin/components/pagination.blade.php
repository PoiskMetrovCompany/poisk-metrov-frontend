@if ($paginator->hasPages())
    <nav aria-label="Пагинация">
        <ul class="pagination justify-content-start">

            {{-- Предыдущая --}}
            <li class="page-item page-item-end {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                @if ($paginator->onFirstPage())
                    <span class="page-link">Предыдущая</span>
                @else
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}">Предыдущая</a>
                @endif
            </li>

            {{-- Номера страниц с троеточиями --}}
            @php
                $currentPage = $paginator->currentPage();
                $lastPage = $paginator->lastPage();
                $pageLinks = [];

                if ($lastPage <= 7) {
                    $pageLinks = range(1, $lastPage);
                } else {
                    $pageLinks[] = 1;

                    if ($currentPage > 3) {
                        $pageLinks[] = '...';
                    }

                    for ($i = max(2, $currentPage - 1); $i <= min($lastPage - 1, $currentPage + 1); $i++) {
                        $pageLinks[] = $i;
                    }

                    if ($currentPage < $lastPage - 2) {
                        $pageLinks[] = '...';
                    }

                    $pageLinks[] = $lastPage;
                }
            @endphp

            @foreach ($pageLinks as $page)
                @if (is_string($page))
                    <li class="page-item page-nav">
                        <a class="page-link link-paginate" href="#">{{ $page }}</a>
                    </li>
                @else
                    <li class="page-item page-nav {{ $page == $currentPage ? 'active' : '' }}">
                        <a class="page-link {{ $page == 1 ? 'link-paginate-start' : ($page == $lastPage ? 'link-paginate-end' : 'link-paginate') }}"
                           href="{{ $paginator->url($page) }}">{{ $page }}</a>
                    </li>
                @endif
            @endforeach

            {{-- Следующая --}}
            <li class="page-item page-item-next {{ $paginator->hasMorePages() ? '' : 'disabled' }}">
                @if ($paginator->hasMorePages())
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}">Следующая</a>
                @else
                    <span class="page-link">Следующая</span>
                @endif
            </li>

        </ul>
    </nav>
@endif
