<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    
</head>
<body class="bg-gray-100 text-gray-800 font-sans antialiased">
    @include('sweetalert::alert')
    
    <div class="flex h-screen overflow-hidden">
        
        <aside class="w-64 bg-gray-900 text-white flex flex-col">
            <div class="h-16 flex items-center justify-center border-b border-gray-800">
                <h1 class="text-2xl font-bold">My App</h1>
            </div>
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <a href="{{ route('customers.index') }}" class="block px-4 py-2 rounded-md transition-colors {{ $active == 'customers' ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    Customers
                </a>
                <a href="{{ route('services.index') }}" class="block px-4 py-2 rounded-md transition-colors {{ $active == 'services' ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    Services
                </a>
                <a href="{{ route('subscriptions.index') }}" class="block px-4 py-2 rounded-md transition-colors {{ $active == 'subscriptions' ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    Subscriptions
                </a>
            </nav>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden ">
            <header class="h-16 bg-white shadow-sm flex items-center px-6">
                <h2 class="text-xl font-semibold text-gray-800">Dashboard</h2>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                @yield('content')
            </main>
        </div>
        
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });

        @if(session('toast_success'))
            Toast.fire({ icon: 'success', title: '{{ session('toast_success') }}' });
        @endif

        @if(session('toast_error'))
            Toast.fire({ icon: 'error', title: '{{ session('toast_error') }}' });
        @endif
    </script>

    @stack('scripts')
</body>
</html>