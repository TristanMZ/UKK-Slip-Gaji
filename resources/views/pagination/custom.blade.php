@if ($paginator->hasPages())
<div class="pager">
    @if ($paginator->onFirstPage())
        <span class="pager__btn pager__btn--disabled">Sebelumnya</span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" class="pager__btn">Sebelumnya</a>
    @endif

    <span class="pager__info">Halaman {{ $paginator->currentPage() }} dari {{ $paginator->lastPage() }}</span>

    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="pager__btn">Berikutnya</a>
    @else
        <span class="pager__btn pager__btn--disabled">Berikutnya</span>
    @endif
</div>
@endif
