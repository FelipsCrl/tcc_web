<!doctype html>
<html lang="pt">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Cadastro</title>
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
                        <form class="card card-md" id="registrationForm">
                            <div class="card-body">
                                <h2 class="h2 text-center mb-4">Criar conta</h2>

                                <!-- Nome da Instituição -->
                                <div class="mb-3">
                                    <label class="form-label">Nome da Instituição</label>
                                    <input type="text" class="form-control" name="nome" value="{{ old('nome') }}" placeholder="Nome da instituição" required>
                                </div>

                                <!-- CNPJ -->
                                <div class="mb-3">
                                    <label class="form-label">CNPJ</label>
                                    <input type="text" class="form-control" id="cnpj" name="cnpj" value="{{ old('cnpj') }}" placeholder="CNPJ" required maxlength="18">
                                </div>

                                <!-- Email -->
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email" required>
                                </div>

                                <!-- Senha -->
                                <div class="mb-3">
                                    <label class="form-label">Senha</label>
                                    <input type="password" class="form-control" name="senha" value="{{ old('senha') }}" placeholder="Senha" required>
                                </div>

                                <!-- Telefone e CEP na mesma linha -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Telefone</label>
                                        <input type="tel" class="form-control" name="telefone" value="{{ old('telefone') }}" placeholder="Telefone" id="telefone" required maxlength="15">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">CEP</label>
                                        <input type="text" class="form-control" name="cep" value="{{ old('cep') }}" placeholder="CEP" id="cep"  onblur="buscarEndereco()" maxlength="9">
                                    </div>
                                </div>
                                <!-- Botão para exibir o modal -->
                                <div class="form-footer">
                                    <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#confirmationModal">Criar nova conta</button>
                                </div>
                                <div class="text-center text-secondary mt-3">
                                    Já tem uma conta? <a href="{{route('login')}}" tabindex="-1">Login</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg d-none d-lg-block">
                    <img src="{{asset('template/dist/img/logo/logo2_blue.svg')}}" height="400" class="d-block mx-auto" alt="">
                </div>
            </div>
        </div>
        <!-- Modal de Confirmação -->
        <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmationModalLabel">Confirmar Dados de Endereço</h5>
                    </div>
                    <div class="modal-body">
                        <form id="confirmationForm" action="{{route('instituicao.store')}}" method="POST">
                            @csrf
                            <!-- Campos para confirmação -->
                            <div class="mb-3 d-none">
                                <label class="form-label">Nome da Instituição</label>
                                <input type="text" class="form-control" id="modalNome" name="nome" readonly>
                            </div>

                            <div class="mb-3 d-none">
                                <label class="form-label">CNPJ</label>
                                <input type="text" class="form-control" id="modalCNPJ" name="cnpj" readonly>
                            </div>

                            <div class="mb-3 d-none">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" id="modalEmail" name="email" readonly>
                            </div>

                            <div class="mb-3 d-none">
                                <label class="form-label">Senha</label>
                                <input type="password" class="form-control" id="modalSenha" name="senha" readonly>
                            </div>

                            <div class="mb-3 d-none">
                                <label class="form-label">Telefone</label>
                                <input type="tel" class="form-control" id="modalTelefone" name="telefone" readonly>
                            </div>

                            <div class="mb-3 d-none">
                                <label class="form-label">CEP</label>
                                <input type="text" class="form-control d-none" id="modalCEP" name="cep" readonly>
                            </div>

                            <!-- Endereço -->
                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <label class="form-label">Rua</label>
                                    <input type="text" class="form-control" id="modalRua" name="rua" placeholder="Rua" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Número</label>
                                    <input type="text" class="form-control" id="modalNumero" name="numero" placeholder="Número" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Bairro</label>
                                    <input type="text" class="form-control" id="modalBairro" name="bairro" placeholder="Bairro" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Complemento</label>
                                    <input type="text" class="form-control" id="modalComplemento" name="complemento" placeholder="Complemento">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <label class="form-label">Cidade</label>
                                    <input type="text" class="form-control" id="modalCidade" name="cidade" placeholder="Cidade" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Estado</label>
                                    <input type="text" class="form-control" id="modalEstado" name="estado" maxlength="2" placeholder="Sigla" required>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Confirmar e Enviar</button>
                            </div>
                        </form>
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

            // Função para formatar o telefone
            document.getElementById('telefone').addEventListener('input', function (e) {
                let x = e.target.value.replace(/\D/g, '');
                x = x.replace(/^(\d{2})(\d)/, '($1) $2');
                x = x.replace(/(\d)(\d{4})$/, '$1-$2');
                e.target.value = x;
            });

            // Função para formatar o CEP
            document.getElementById('cep').addEventListener('input', function (e) {
                let x = e.target.value.replace(/\D/g, '');
                x = x.replace(/^(\d{5})(\d)/, '$1-$2');
                e.target.value = x;
            });

            // Função para formatar o CNPJ
            document.getElementById('cnpj').addEventListener('input', function (e) {
                let x = e.target.value.replace(/\D/g, '');
                x = x.replace(/^(\d{2})(\d)/, '$1.$2');
                x = x.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
                x = x.replace(/\.(\d{3})(\d)/, '.$1/$2');
                x = x.replace(/(\d{4})(\d)/, '$1-$2');
                e.target.value = x;
            });
        </script>
        <script>
            document.getElementById('registrationForm').addEventListener('click', function (event) {
                event.preventDefault(); // Evitar o envio do formulário
                const form = this;

                // Preencher o modal com dados do formulário
                document.getElementById('modalNome').value = form.nome.value;
                document.getElementById('modalCNPJ').value = form.cnpj.value;
                document.getElementById('modalEmail').value = form.email.value;
                document.getElementById('modalSenha').value = form.senha.value;
                document.getElementById('modalTelefone').value = form.telefone.value;
                document.getElementById('modalCEP').value = form.cep.value;
            });

            document.getElementById('registrationForm').addEventListener('submit', function (event) {
                event.preventDefault(); // Evitar o envio do formulário

                // Submeter o formulário principal
                document.getElementById('confirmationForm').submit();
            });

            // Função para redirecionar ao login
            document.querySelector('a[href="{{route('login')}}"]').addEventListener('click', function (event) {
                event.stopPropagation(); // Impedir que o evento clique do formulário interfira no link
            });
        </script>
        <script>
            function buscarEndereco() {
                const cep = document.getElementById('cep').value.replace(/\D/g, '');

                // Consulta a API ViaCEP
                fetch(`https://viacep.com.br/ws/${cep}/json/`)
                    .then(response => response.json())
                    .then(data => {
                        if (!data.erro) {
                            // Preencher os campos do endereço com os dados da API
                            document.getElementById('modalRua').value = data.logradouro;
                            document.getElementById('modalBairro').value = data.bairro;
                            document.getElementById('modalCidade').value = data.localidade;
                            document.getElementById('modalEstado').value = data.uf;
                        }
                    })
                    .catch(() => {
                        //alert('Erro ao buscar o CEP!');
                    });
            }
            </script>
</body>

</html>
