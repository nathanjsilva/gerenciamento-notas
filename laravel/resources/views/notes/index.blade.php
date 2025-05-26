@extends('layouts.app')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="modal fade" id="createCategoryModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="categoryForm">
                    <div class="modal-header">
                        <h5 class="modal-title">Nova Categoria</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" name="name" class="form-control" placeholder="Nome da categoria" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewCategoriesModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Categorias</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <ul class="list-group" id="categoryList">
                        @foreach ($categories as $category)
                            <li class="list-group-item d-flex justify-content-between align-items-center mt-3">
                                {{ $category->name }}
                                <button class="btn btn-sm btn-outline-danger btn-delete-category"
                                        data-id="{{ $category->id }}"
                                        data-name="{{ $category->name }}">
                                    <i class="bi bi-trash"></i> Excluir
                                </button>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8 col-lg-6">
        <div class="card shadow">
            <div class="card-body p-4">
                <h3 class="text-center mb-4">Nova Anotação</h3>
                <div class="d-flex justify-content-between mb-3 gap-2">
                    <button type="button" class="btn btn-secondary w-50" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
                        Criar Categoria
                    </button>
                    <button type="button" class="btn btn-outline-primary w-50" data-bs-toggle="modal" data-bs-target="#viewCategoriesModal">
                        Ver Categorias
                    </button>
                </div>

                <form id="noteForm">
                    @csrf
                    <div class="mb-3">
                        <label for="title" class="form-label">Título</label>
                        <input type="text" name="title" class="form-control" required placeholder="Digite o título">
                    </div>

                    <div class="mb-3">
                        <label for="text" class="form-label">Texto da Anotação</label>
                        <textarea name="text" rows="4" class="form-control" required placeholder="Escreva sua anotação aqui..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="category" class="form-label">Categoria</label>
                        <select name="category_id" class="form-control" required>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Salvar Anotação</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirmDeleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirmar Exclusão</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja excluir a categoria <strong id="categoryNameToDelete"></strong>?</p>
                <input type="hidden" id="categoryIdToDelete">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Excluir</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="toast" class="toast align-items-center text-white bg-success border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body" id="toastMessage"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

<script>
    const form = document.getElementById('noteForm');
    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        const formData = new FormData(form);

        try {
            const res = await fetch("{{ route('notes.store') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                },
                body: formData,
                credentials: 'same-origin'
            });

            const data = await res.json();

            if (res.ok) {
                showToast(data.message || 'Anotação salva com sucesso!');
                setTimeout(() => window.location.href = '/', 1000);
            } else {
                showToast(data.message || 'Erro ao salvar anotação.', false);
            }
        } catch (err) {
            console.log(err)
            showToast('Erro inesperado ao salvar.', false);
        }
    });

    function showToast(message, success = true) {
        const toastEl = document.getElementById('toast');
        const toastMessage = document.getElementById('toastMessage');
        toastEl.classList.remove('bg-success', 'bg-danger');
        toastEl.classList.add(success ? 'bg-success' : 'bg-danger');
        toastMessage.textContent = message;

        const toast = new bootstrap.Toast(toastEl);
        toast.show();
    }

    document.getElementById('categoryForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        try {
            const res = await fetch("{{ route('categories.store') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: formData
            });

            const data = await res.json();

            console.log(data)
            if (res.ok) {
                showToast(data.message || 'Categoria criada com sucesso!');
                e.target.reset();
                const option = document.createElement('option');
                option.value = data.category.id;
                option.text = data.category.name;
                document.querySelector('select[name="category_id"]').append(option);
                document.querySelector('#createCategoryModal .btn-close').click();
            } else {
                showToast(data.message || 'Erro ao criar categoria.', false);
            }
        } catch (err) {
            console.log(err)
            showToast('Erro inesperado ao salvar categoria.', false);
        }
    });

    let categoryIdToDelete = null;

    document.addEventListener('click', function (e) {
        const deleteBtn = e.target.closest('.btn-delete-category');
        if (deleteBtn) {
            categoryIdToDelete = deleteBtn.dataset.id;
            const categoryName = deleteBtn.dataset.name;

            document.getElementById('categoryIdToDelete').value = categoryIdToDelete;
            document.getElementById('categoryNameToDelete').textContent = categoryName;

            const confirmModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
            confirmModal.show();
        }
    });

    document.getElementById('confirmDeleteBtn').addEventListener('click', async () => {
        const id = document.getElementById('categoryIdToDelete').value;

        try {
            const res = await fetch(`/categories/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'Accept': 'application/json'
                }
            });

            const data = await res.json();

            if (res.ok) {
                showToast(data.message || 'Categoria excluída!');

                document.querySelectorAll(`[data-id="${id}"]`).forEach(el => el.closest('li')?.remove());
                document.querySelector(`select[name="category_id"] option[value="${id}"]`)?.remove();
            } else {
                showToast(data.message || 'Erro ao excluir categoria.', false);
            }
        } catch (err) {
            console.error(err);
            showToast('Erro inesperado ao excluir categoria.', false);
        }

        bootstrap.Modal.getInstance(document.getElementById('confirmDeleteModal')).hide();
    });

</script>
@endsection
