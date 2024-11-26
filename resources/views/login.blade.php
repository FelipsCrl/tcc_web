<!doctype html>
<html lang="pt">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Login</title>
    <!-- CSS files -->
    <link href="{{asset('template/dist/css/tabler.min.css?1692870487')}}" rel="stylesheet" />
    <link href="{{asset('template/dist/css/tabler-flags.min.css?1692870487')}}" rel="stylesheet" />
    <link href="{{asset('template/dist/css/tabler-payments.min.css?1692870487')}}" rel="stylesheet" />
    <link href="{{asset('template/dist/css/tabler-vendors.min.css?1692870487')}}" rel="stylesheet" />
    <link href="{{asset('template/dist/css/demo.min.css?1692870487')}}" rel="stylesheet" />
    <style>
        @import url('https://rsms.me/inter/inter.css');
         :root {
            --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
        }

        body {
            font-feature-settings: "cv03", "cv04", "cv11";
        }
    </style>
</head>

<body class="d-flex flex-column">
    @if (session('success'))
    <div class="alert alert-success" id="success-alert">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger" id="error-alert">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <script src="{{asset('template/dist/js/demo-theme.min.js?1692870487')}}"></script>
    <div class="page page-center">
        <div class="container container-normal py-4">
            <div class="row align-items-center g-4">
                <div class="col-lg">
                    <div class="container-tight">
                        <div class="card card-md">
                            <div class="card-body">
                                <h2 class="h2 text-center mb-4">Faça Login</h2>
                                <form action="{{ route('login.login') }}" method="post" autocomplete="off" novalidate>
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" placeholder="User@email.com" required>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">
                                            Senha
                                            <span class="form-label-description">
                                                <a href="/forgot-password">Esqueci minha senha</a>
                                            </span>
                                        </label>
                                        <div class="input-group input-group-flat">
                                            <input type="password" name="password" class="form-control" placeholder="Senha" required>
                                        </div>
                                    </div>
                                    <div class="form-footer">
                                        <button type="submit" class="btn btn-primary w-100">Fazer login</button>
                                    </div>
                                </form>
                            </div>
                            <!-- <div class="hr-text">ou</div> -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <!-- <a href="#" class="btn w-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-brand-google">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M20.945 11a9 9 0 1 1 -3.284 -5.997l-2.655 2.392a5.5 5.5 0 1 0 2.119 6.605h-4.125v-3h7.945z" />
                                            </svg> Login com Google
                                        </a> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center text-secondary mt-3">
                            Não tem uma conta? <a href="{{route('instituicao.create')}}" tabindex="-1">Cadastrar</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg d-none d-lg-block">
                    <img src="{{asset('template/dist/img/logo/logo2_blue.svg')}}" height="400" class="d-block mx-auto" alt="">
                </div>
            </div>
        </div>
    </div>
    <!-- Tabler Core -->
    <script src="{{asset('template/dist/js/tabler.min.js?1692870487')}}" defer></script>
    <script src="{{asset('template/dist/js/demo.min.js?1692870487')}}" defer></script>
    <script>
        window.onload = function() {
            setTimeout(function() {
                var successAlert = document.getElementById('success-alert');
                if (successAlert) {
                    successAlert.style.display = 'none';
                }

                var errorAlert = document.getElementById('error-alert');
                if (errorAlert) {
                    errorAlert.style.display = 'none';
                }
            }, 5000); // 5000ms = 5 segundos
        };
    </script>
</body>

</html>
