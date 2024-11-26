<!doctype html>
<html lang="pt">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>
      Redefinir Senha
    </title>
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
          <a href="#" class="navbar-brand navbar-brand-autodark">
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
          action="{{ route('password.update') }}"
        >
        @csrf
          <!-- Token necessário para redefinição -->
          <input type="hidden" name="token" value="{{ $request->route('token') }}">

          <div class="card-body">
            <h2 class="card-title text-center mb-4">Redefinir Senha</h2>

            <!-- Exibição de erros -->
            @if ($errors->any())
              <div class="alert alert-danger">
                <ul class="mb-0">
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            <!-- Email -->
            <div class="mb-3">
              <label class="form-label" for="email">Email</label>
              <input
                id="email"
                type="email"
                class="form-control"
                name="email"
                value="{{ old('email', $request->email) }}"
                required
                autofocus
                autocomplete="username"
              />
            </div>

            <!-- Nova Senha -->
            <div class="mb-3">
              <label class="form-label" for="password">Nova Senha</label>
              <input
                id="password"
                type="password"
                class="form-control"
                name="password"
                required
                autocomplete="new-password"
              />
            </div>

            <!-- Confirmação da Nova Senha -->
            <div class="mb-3">
              <label class="form-label" for="password_confirmation">Confirmar Nova Senha</label>
              <input
                id="password_confirmation"
                type="password"
                class="form-control"
                name="password_confirmation"
                required
                autocomplete="new-password"
              />
            </div>

            <div class="form-footer">
              <button type="submit" class="btn btn-primary w-100">
                <!-- Ícone SVG -->
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
                  <path d="M3 7l9 6l9 -6" />
                  <path d="M21 15v-6a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v6" />
                  <path d="M12 12l-9 6" />
                  <path d="M12 12l9 6" />
                </svg>
                Redefinir Senha
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
    <script src="{{asset('template/dist/js/tabler.min.js')}}" defer></script>
    <script src="{{asset('template/dist/js/demo.min.js')}}" defer></script>
  </body>
</html>
