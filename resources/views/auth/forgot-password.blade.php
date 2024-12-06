<!doctype html>
<html lang="pt">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>
      Esqueceu Senha
    </title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- CSS files -->
    <link href="{{asset('template/dist/css/tabler.min.css')}}" rel="stylesheet" />
    <link href="{{asset('template/dist/css/tabler-flags.min.css')}}" rel="stylesheet" />
    <link href="{{asset('template/dist/css/tabler-payments.min.css')}}" rel="stylesheet" />
    <link href="{{asset('template/dist/css/tabler-vendors.min.css')}}" rel="stylesheet" />
    <link href="{{asset('template/dist/css/demo.min.css')}}" rel="stylesheet" />
    <style>
      @import url('https://rsms.me/inter/inter.css');
      :root {
        --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont,
          San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
      }
      body {
        font-feature-settings: "cv03", "cv04", "cv11";
      }
    </style>
  </head>
  <body class="d-flex flex-column">
    <script src="{{asset('template/dist/js/demo-theme.min.js')}}"></script>
    <div class="page page-center">
      <div class="container container-tight py-4">
        <div class="text-center mb-4">
          <a href="." class="navbar-brand navbar-brand-autodark">
            <img
              src="{{asset('template/dist/img/logo/logo6.svg')}}"
              style="width: auto; height: auto; border-radius: 12px;"
              alt="Mãos Solidárias"
              class="navbar-brand-image"
            />
          </a>
        </div>
        <form
          class="card card-md"
          method="POST"
          action="{{route('password.email')}}"
        >
        @csrf
          <div class="card-body">
            <h2 class="card-title text-center mb-4">Esqueceu senha</h2>
            <p class="text-secondary mb-4">
              Digite seu email e será enviado por email os passos para redefinir sua senha
            </p>
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" id="email" class="form-control" name="email" placeholder="Email" required autofocus />
            </div>
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <!-- Erros de validação -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="form-footer">
              <button type="submit" class="btn btn-primary w-100">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="icon"
                  width="24"
                  height="24"
                  viewBox="0 0 24 24"
                  stroke-width="2"
                  stroke="currentColor"
                  fill="none"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                >
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" />
                  <path d="M3 7l9 6l9 -6" />
                </svg>
                Enviar email
              </button>
            </div>
          </div>
        </form>
        <div class="text-center text-secondary mt-3">
          Desejo <a href="{{route('login')}}">voltar</a> para a tela de login.
        </div>
      </div>
    </div>
    <script src="{{asset('template/dist/js/tabler.min.js')}}" defer></script>
    <script src="{{asset('template/dist/js/demo.min.js')}}" defer></script>
  </body>
</html>
