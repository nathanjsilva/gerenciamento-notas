@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Minhas Anotações</h2>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-danger">Sair</button>
        </form>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @foreach ($notes as $note)
            <div class="col">
                <div class="card h-100 shadow-sm note-card" data-bs-toggle="modal" data-bs-target="#noteModal"
                    data-title="{{ $note->title }}"
                    data-text="{{ $note->text }}"
                    data-category="{{ $note->category->name ?? 'Sem categoria' }}"
                >
                    <div class="card-body">
                        <h5 class="card-title">{{ $note->title }}</h5>
                        <p class="card-text text-truncate">{{ $note->text }}</p>
                        <span class="badge bg-secondary">{{ $note->category->name ?? 'Sem categoria' }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <a href="{{ route('notes.index') }}"
        class="btn btn-primary shadow position-fixed d-flex align-items-center justify-content-center"
        style="bottom: 30px; right: 30px; width: 60px; height: 60px; z-index: 1050;"
        title="Nova Anotação">
        <i class="bi bi-plus-lg fs-4"></i>
    </a>
</div>

<div class="modal fade" id="noteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="noteModalTitle"></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <p id="noteModalText"></p>
            <small class="text-muted">Categoria: <span id="noteModalCategory"></span></small>
        </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.querySelectorAll('.note-card').forEach(card => {
        card.addEventListener('click', () => {
            document.getElementById('noteModalTitle').textContent = card.dataset.title;
            document.getElementById('noteModalText').textContent = card.dataset.text;
            document.getElementById('noteModalCategory').textContent = card.dataset.category;
        });
    });
</script>
@endsection
