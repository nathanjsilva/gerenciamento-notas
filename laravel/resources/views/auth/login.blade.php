@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-body p-4">
                <h3 class="mb-4 text-center">Entrar</h3>
                <form id="loginForm">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" class="form-control" name="email" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Senha</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Entrar</button>
                    </div>
                </form>
                <div class="text-center mt-3">
                    <a href="{{ route('register.form') }}">Não tem conta? Registrar</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('loginForm').addEventListener('submit', async function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        try {
            const res = await fetch('{{ route('login') }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: formData
            });
            const data = await res.json();
            if (res.ok) {
                showToast('Login realizado com sucesso!');
                setTimeout(() => window.location.href = '/notes', 1000);
            } else {
                showToast(data.message || 'Credenciais inválidas.', false);
            }
        } catch (err) {
            showToast('Erro inesperado no login.', false);
        }
    });
</script>
@endsection
