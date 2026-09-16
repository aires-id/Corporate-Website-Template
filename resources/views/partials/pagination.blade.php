{{-- SPDX-License-Identifier: NCSA --}}
@if($paginator->hasPages())
  <nav class="pagination" aria-label="Pagination">
    @if($paginator->onFirstPage())<span>← Sebelumnya</span>@else<a href="{{ $paginator->previousPageUrl() }}">← Sebelumnya</a>@endif
    <span>Halaman {{ $paginator->currentPage() }} dari {{ $paginator->lastPage() }}</span>
    @if($paginator->hasMorePages())<a href="{{ $paginator->nextPageUrl() }}">Berikutnya →</a>@else<span>Berikutnya →</span>@endif
  </nav>
@endif
