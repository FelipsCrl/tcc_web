<!doctype html>
<html lang="pt">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
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

        .navbar-brand-image {
            height: 3rem;
            width: auto;
        }

        .nav-link.active .icon {
            stroke: white;
        }

        @yield('style')
    </style>
</head>

<body>
    <!-- Offcanvas para Sucesso -->
    <div class="offcanvas offcanvas-top" tabindex="-1" id="offcanvasTopSuccess" aria-labelledby="offcanvasTopLabelSuccess">
        <div class="offcanvas-header">
            <h2 class="offcanvas-title text-success" id="offcanvasTopLabelSuccess">Sucesso</h2>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="text-success">{{ session('success') }}</div>
            <div class="mt-3">
                <button class="btn btn-success" type="button" data-bs-dismiss="offcanvas">
                    Fechar
                </button>
            </div>
        </div>
    </div>

    <!-- Offcanvas para Erros -->
    <div class="offcanvas offcanvas-top" tabindex="-1" id="offcanvasTopError" aria-labelledby="offcanvasTopLabelError">
        <div class="offcanvas-header">
            <h2 class="offcanvas-title text-danger" id="offcanvasTopLabelError">Erro</h2>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="text-danger">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <div class="mt-3">
                <button class="btn btn-danger" type="button" data-bs-dismiss="offcanvas">
                    Fechar
                </button>
            </div>
        </div>
    </div>

    <script src="{{asset('template/dist/js/demo-theme.min.js?1692870487')}}"></script>
    <div class="page">
        <header class="navbar navbar-expand-md navbar-overlap d-print-none" data-bs-theme="dark">
            <div class="container-xl">
                <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu" aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <h1 class="navbar-brand navbar-brand-image navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
                    <a href="">
                        <img src="{{asset('template/dist/img/logo/logo7.svg')}}" width="110" height="32" alt="Mãos Solidárias" class="navbar-brand-image ">
                    </a>
                </h1>
                <div class="navbar-nav flex-row order-md-last">
                    <div class="d-none d-md-flex d-md-flex ">
                        <a href="?theme=dark" class="nav-link px-0 hide-theme-dark" data-bs-toggle="tooltip" data-bs-placement="bottom" aria-label="Enable dark mode" data-bs-original-title="Ativar modo escuro">
                            <!-- Ícone de modo escuro -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z"></path>
                            </svg>
                        </a>
                        <a href="?theme=light" class="nav-link px-0 hide-theme-light" data-bs-toggle="tooltip" data-bs-placement="bottom" aria-label="Enable light mode" data-bs-original-title="Ativar modo claro">
                            <!-- Ícone de modo claro -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"></path>
                                <path d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7"></path>
                            </svg>
                        </a>
                    </div>

                    <!-- Botão de Logout -->
                    <div class="nav-item me-3">
                        <a href="" class="nav-link px-0" aria-label="Logout" data-bs-toggle="modal" data-bs-toggle="modal" data-bs-target="#modal-small" data-bs-placement="bottom" title="Sair">
                            <!-- Ícone de Logout -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-logout-2">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M10 8v-2a2 2 0 0 1 2 -2h7a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-7a2 2 0 0 1 -2 -2v-2" />
                                <path d="M15 12h-12l3 -3" />
                                <path d="M6 15l-3 -3" />
                            </svg>
                        </a>
                    </div>

                    <!-- Menu de usuário com avatar -->
                    <div class="nav-item">
                        <a href="{{route('instituicao.index')}}" class="nav-link d-flex lh-1 text-reset p-0" aria-label="Open user menu">
                            @php
                                // Verifica se o usuário possui uma foto de perfil e se o arquivo realmente existe
                                $profilePhotoPath = 'storage/profile-photos/' . basename(Auth::user()->profile_photo_url);
                                $profilePhotoExists = Auth::user()->profile_photo_url && file_exists(public_path($profilePhotoPath));
                            @endphp

                            <span class="avatar avatar-sm"
                                @if($profilePhotoExists)
                                    style="background-image: url('{{ asset($profilePhotoPath) }}');"
                                @else
                                    style="background-color: #EBF4FF; color: #7F9CF5; display: flex; align-items: center; justify-content: center; font-weight: bold;"
                                @endif>
                                @unless($profilePhotoExists)
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                @endunless
                            </span>
                            <div class="d-none d-xl-block ps-2">
                                <div>{{ Auth::user()->name ?? '' }}</div>
                                <div class="mt-1 small text-secondary">Instituição</div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="navbar-collapse collapse show" id="navbar-menu">
                    <div class="d-flex flex-column flex-md-row flex-fill align-items-stretch align-items-md-center">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('home')}}">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/home -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M5 12l-2 0l9 -9l9 9l-2 0"></path><path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7"></path><path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6"></path></svg>
                                    </span>
                                    <span class="nav-link-title">
                                        Home
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('doacao.index') }}">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/home -->
                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-heart-handshake"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M19.5 12.572l-7.5 7.428l-7.5 -7.428a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572" /><path d="M12 6l-3.293 3.293a1 1 0 0 0 0 1.414l.543 .543c.69 .69 1.81 .69 2.5 0l1 -1a3.182 3.182 0 0 1 4.5 0l2.25 2.25" /><path d="M12.5 15.5l2 2" /><path d="M15 13l2 2" /></svg>
                                    </span>
                                    <span class="nav-link-title">
                                        Doação
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('voluntario.index') }}">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/home -->
                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-user"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                                    </span>
                                    <span class="nav-link-title">
                                        Voluntário
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('evento.index')}}">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/home -->
                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-event"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /><path d="M16 3l0 4" /><path d="M8 3l0 4" /><path d="M4 11l16 0" /><path d="M8 15h2v2h-2z" /></svg>
                                    </span>
                                    <span class="nav-link-title">
                                        Evento
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('solicitacao.index')}}">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/home -->
                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-progress-help"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 16v.01" /><path d="M12 13a2 2 0 0 0 .914 -3.782a1.98 1.98 0 0 0 -2.414 .483" /><path d="M10 20.777a8.942 8.942 0 0 1 -2.48 -.969" /><path d="M14 3.223a9.003 9.003 0 0 1 0 17.554" /><path d="M4.579 17.093a8.961 8.961 0 0 1 -1.227 -2.592" /><path d="M3.124 10.5c.16 -.95 .468 -1.85 .9 -2.675l.169 -.305" /><path d="M6.907 4.579a8.954 8.954 0 0 1 3.093 -1.356" /></svg>
                                    </span>
                                    <span class="nav-link-title">
                                        Solicitação
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>
        <div class="page-wrapper">
            <!-- Page header -->
            @yield('header')
            <!-- Page body -->
            @yield('body')
        </div>
    </div>
    @yield('modal')
    <div class="modal modal-blur fade" id="modal-small" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-title">Tem certeza?</div>
                    <div>Deseja realmente sair da sua conta?</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Cancelar</button>
                    <a href="{{route('logout')}}" class="btn btn-danger">Sair</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Libs JS -->
    <script src="{{asset('template/dist/libs/apexcharts/dist/apexcharts.min.js?1692870487')}}" defer></script>
    <!-- Tabler Core -->
    <script src="{{asset('template/dist/js/tabler.min.js?1692870487')}}" defer></script>
    <script src="{{asset('template/dist/js/demo.min.js?1692870487')}}" defer></script>
    @yield('script')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Obtém a URL completa da página atual
            const currentUrl = window.location.href;

            // Seleciona todos os itens de menu, exceto o botão de logout
            const menuItems = document.querySelectorAll('.navbar-nav .nav-link:not([aria-label="Logout"])');

            // Verifica cada item do menu e adiciona a classe "active" ao correspondente
            menuItems.forEach(item => {
                const href = item.href; // Pega a URL completa do item de menu
                if (currentUrl === href) {
                    item.classList.add('active');
                } else if (currentUrl.startsWith(href)) {
                    // Se a URL da página começa com o href, também considera como ativo
                    item.classList.add('active');
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Verificar se existe uma mensagem de sucesso
            @if (session('success'))
                var offcanvasSuccess = new bootstrap.Offcanvas(document.getElementById('offcanvasTopSuccess'));
                offcanvasSuccess.show();
            @endif

            // Verificar se existem erros
            @if ($errors->any())
                var offcanvasError = new bootstrap.Offcanvas(document.getElementById('offcanvasTopError'));
                offcanvasError.show();
            @endif
        });
    </script>
</body>

</html>
