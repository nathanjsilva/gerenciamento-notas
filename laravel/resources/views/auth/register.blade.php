@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-body p-4">
                <h3 class="mb-4 text-center">Registrar</h3>
                <form id="registerForm">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome</label>
                        <input type="text" class="form-control" name="name" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Senha</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirmar Senha</label>
                        <input type="password" class="form-control" name="password_confirmation" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Registrar</button>
                    </div>
                </form>
                <div class="text-center mt-3">
                    <a href="{{ route('login.form') }}">Já tem uma conta? Entrar</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('registerForm').addEventListener('submit', async function (e) {
    e.preventDefault();
        const formData = new FormData(this);
        try {
            const res = await fetch('{{ route('register') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            });

            const data = await res.json();
            console.log(data)
            if (res.ok) {
                showToast(data.message || 'Registrado com sucesso!');
                setTimeout(() => window.location.href = '{{ route('login.form') }}', 1000);
            } else {
                showToast(data.message || 'Erro ao registrar.', false);
            }
        } catch (err) {
            showToast('Erro inesperado no registro.', false);
        }
    });

</script>
@endsection
