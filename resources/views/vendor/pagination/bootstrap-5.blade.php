@if ($paginator->hasPages())
    <div class="mt-4">
        <nav aria-label="Navegação de páginas">
            <ul class="pagination pagination-sm justify-content-center mb-2">
                @if ($paginator->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link">&laquo; Anterior</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo; Anterior</a>
                    </li>
                @endif

                @foreach ($elements as $element)
                    @if (is_string($element))
                        <li class="page-item disabled">
                            <span class="page-link">{{ $element }}</span>
                        </li>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li class="page-item active" aria-current="page">
                                    <span class="page-link">{{ $page }}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                @if ($paginator->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Próximo &raquo;</a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link">Próximo &raquo;</span>
                    </li>
                @endif
            </ul>
        </nav>

        <div class="text-center text-muted small">
            Mostrando 
            <strong>{{ $paginator->firstItem() ?? 0 }}</strong> 
            a 
            <strong>{{ $paginator->lastItem() ?? 0 }}</strong> 
            de 
            <strong>{{ $paginator->total() }}</strong> 
            registros
        </div>
    </div>
@endif