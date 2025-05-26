<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Notas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="min-height: 100vh;">

    <main class="container">
        @yield('content')
    </main>

    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999;">
        <div id="toastContainer"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showToast(message, isSuccess = true) {
            const toastId = 'toast-' + Date.now();
            const toastHtml = `
                <div id="${toastId}" class="toast align-items-center text-white bg-${isSuccess ? 'success' : 'danger'} border-0 mb-2" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">${message}</div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Fechar"></button>
                    </div>
                </div>`;
            const container = document.getElementById('toastContainer');
            container.insertAdjacentHTML('beforeend', toastHtml);
            const toastElement = new bootstrap.Toast(document.getElementById(toastId), { delay: 4000 });
            toastElement.show();
        }
    </script>

    @yield('scripts')
</body>
</html>
