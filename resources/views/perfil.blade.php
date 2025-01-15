@extends('layout')

@section('style')
.content-section {
    display: none;
}

.content-section.active {
    display: block;
}
@endsection
@section('header')
<title>Perfil</title>
<div class="page-header d-print-none text-white">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <!-- Page pre-title -->
                <div class="page-pretitle">
                    Setor
                </div>
                <h2 class="page-title">
                    Perfil
                </h2>
            </div>
        </div>
    </div>
</div>
@endsection

@section('body')
<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="row g-0">
                <div class="col-12 col-md-3 border-end">
                    <div class="card-body">
                        <h4 class="subheader">Dados</h4>
                        <div class="list-group list-group-transparent">
                            <a href="#" id="menu-conta" class="list-group-item list-group-item-action d-flex align-items-center active" onclick="showSection('conta')">Conta</a>
                            <a href="#" id="menu-endereco" class="list-group-item list-group-item-action d-flex align-items-center" onclick="showSection('endereco')">Endereço</a>
                            <a href="#" id="menu-contato" class="list-group-item list-group-item-action d-flex align-items-center" onclick="showSection('contato')">Contato</a>
                            <a href="#" id="menu-funcionamento" class="list-group-item list-group-item-action d-flex align-items-center" onclick="showSection('funcionamento')">Funcionamento</a>
                        </div>
                        <!--
                        <h4 class="subheader mt-4">Configurações</h4>
                        <div class="list-group list-group-transparent">
                            <a href="#" id="menu-doacoes" class="list-group-item list-group-item-action" onclick="showSection('doacoes')">Doações</a>
                            <a href="#" id="menu-voluntariado" class="list-group-item list-group-item-action" onclick="showSection('voluntariado')">Voluntariado</a>
                        </div>
                        -->
                    </div>
                </div>
                <div class="col-12 col-md-9 d-flex flex-column">
                    <div id="conta" class="content-section active">
                        <form class="card-body" id="form-menu-conta" action="{{ route('instituicao.update', $instituicao->id_instituicao) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <h2 class="mb-4">Conta</h2>
                            <h3 class="card-title">Detalhes do Perfil</h3>
                            <div class="row align-items-center">
                                <input type="hidden" name="menu" value="1">
                                <div class="col-auto">
                                    @php
                                        // Verifica se o usuário possui uma foto de perfil e se o arquivo realmente existe
                                        $profilePhotoPath = 'storage/profile-photos/' . basename(Auth::user()->profile_photo_url);
                                        $profilePhotoExists = Auth::user()->profile_photo_url && file_exists(public_path($profilePhotoPath));
                                    @endphp

                                    <span class="avatar avatar-xl"
                                        @if($profilePhotoExists)
                                            style="background-image: url('{{ asset($profilePhotoPath) }}');"
                                        @else
                                            style="background-color: #EBF4FF; color: #7F9CF5; display: flex; align-items: center; justify-content: center; font-weight: bold;"
                                        @endif>
                                        @unless($profilePhotoExists)
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        @endunless
                                    </span>
                                </div>
                                <div class="col-auto">
                                    <input type="file" id="upload" name="imagem_perfil" accept="image/*" style="display:none;" />
                                    <a href="#" id="change-photo" class="btn">Mudar foto</a>
                                </div>
                            </div>
                            <h3 class="card-title mt-4">Perfil da Instituição</h3>
                            <div class="row g-3">
                                <div class="col-md">
                                    <div class="form-label">Nome</div>
                                    <input type="text" class="form-control" name="nome" value="{{ $instituicao->usuario->name ?? '' }}">
                                </div>
                                <div class="col-md">
                                    <div class="form-label">CNPJ</div>
                                    <input type="text" class="form-control" value="{{ $instituicao->cnpj_instituicao ?? '' }}" readonly>
                                </div>
                            </div>
                            <div class="row g-3 mt-2">
                                <div class="col-md">
                                    <div class="form-label">Email</div>
                                    <input type="text" class="form-control w-auto" value="{{ $instituicao->usuario->email ?? '' }}" readonly>
                                </div>
                                <div class="col-md">
                                    <div class="form-label">Senha</div>
                                    <a href="#" class="btn" data-bs-toggle="modal" data-bs-toggle="modal" data-bs-target="#modal-senha">Mudar senha</a>
                                </div>
                            </div>
                            <!--
                            <h3 class="card-title mt-4">Pix (forma entrada monetário)</h3>
                            <div class="row g-3">
                                <div class="col-md">
                                    <div class="form-label">Tipo da chave pix</div>
                                    <input type="text" class="form-control" value="Tabler">
                                </div>
                                <div class="col-md">
                                    <div class="form-label">Chave pix</div>
                                    <input type="text" class="form-control" value="560afc32">
                                </div>
                            </div>
                            -->
                            <h3 class="card-title mt-4">Sobre a instituição</h3>
                            <div>
                                <textarea class="form-control" placeholder="Fale sobre..." rows="2" name="descricao" maxlength="200">{{ $instituicao->descricao_instituicao ?? '' }}</textarea>
                            </div>
                        </form>
                    </div>
                    <div id="endereco" class="content-section">
                        <form class="card-body" id="form-menu-endereco" action="{{ route('instituicao.update', $instituicao->id_instituicao) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <h2 class="mb-4">Endereço</h2>
                            <div class="row g-3">
                                <input type="hidden" name="menu" value="2">
                                <div class="col-md">
                                    <div class="form-label">CEP</div>
                                    <input type="text" name="cep" class="form-control" value="{{ $instituicao->endereco->cep_endereco ?? '' }}">
                                </div>
                                <div class="col-md">
                                    <div class="form-label">Estado</div>
                                    <input type="text" name="estado" class="form-control" value="{{ $instituicao->endereco->estado_endereco ?? '' }}" maxlength="2">
                                </div>
                            </div>

                            <div class="row g-3 mt-2">
                                <div class="col-md-6">
                                    <div class="form-label">Cidade</div>
                                    <input type="text" class="form-control" name="cidade" value="{{ $instituicao->endereco->cidade_endereco ?? '' }}">
                                </div>
                            </div>

                            <div class="row g-3 mt-2">
                                <div class="col-md">
                                    <div class="form-label">Rua</div>
                                    <input type="text" class="form-control" name="rua" value="{{ $instituicao->endereco->logradouro_endereco ?? '' }}">
                                </div>
                                <div class="col-md">
                                    <div class="form-label">Número</div>
                                    <input type="text" class="form-control" name="numero" value="{{ $instituicao->endereco->numero_endereco ?? '' }}">
                                </div>
                            </div>

                            <div class="row g-3 mt-2 mb-4">
                                <div class="col-md">
                                    <div class="form-label">Bairro</div>
                                    <input type="text" class="form-control" name="bairro" value="{{ $instituicao->endereco->bairro_endereco ?? '' }}">
                                </div>
                                <div class="col-md">
                                    <div class="form-label">Complemento</div>
                                    <input type="text" class="form-control" name="complemento" value="{{ $instituicao->endereco->complemento_endereco ?? '' }}">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div id="contato" class="content-section">
                        <form class="card-body" id="form-menu-contato" action="{{ route('instituicao.update', $instituicao->id_instituicao) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <h2 class="mb-4">Contato</h2>
                            <div class="row mt-4">
                                <input type="hidden" name="menu" value="3">
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-label">Telefone</div>
                                    <input type="text" class="form-control" id="telefone" name="telefone" value="{{ $instituicao->contato->telefone_contato ?? '' }}" maxlength="15">
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-label">Whatsapp</div>
                                    <input type="text" class="form-control" id="whatsapp" name="whatsapp" value="{{ $instituicao->contato->whatsapp_contato ?? '' }}" maxlength="15">
                                </div>
                            </div>
                            <h3 class="card-title mt-4">Redes Sociais</h3>
                            <div class="row">
                                <div class="col-md">
                                    <div class="form-label">Instagram</div>
                                    <input type="text" class="form-control" name="instagram" value="{{ $instituicao->contato->instagram_contato ?? '' }}">
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md">
                                    <div class="form-label">Facebook</div>
                                    <input type="text" class="form-control" name="facebook" value="{{ $instituicao->contato->facebook_contato ?? '' }}">
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md">
                                    <div class="form-label">Site Institucional</div>
                                    <input type="text" class="form-control" name="site" value="{{ $instituicao->contato->site_contato ?? '' }}">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div id="funcionamento" class="content-section">
                        <form class="card-body" id="form-menu-funcionamento" action="{{ route('instituicao.update', $instituicao->id_instituicao) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <h2>Funcionamento</h2>
                            <input type="hidden" name="menu" value="4">
                            <div class="container table-container">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">Dia da Semana</th>
                                                <th class="text-center" scope="col">Horário de Abertura</th>
                                                <th class="text-center" scope="col">Horário de Encerramento</th>
                                                <th class="text-center" scope="col">Não Funciona</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $diasSemana = ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo'];
                                            @endphp

                                            @foreach($diasSemana as $dia)
                                                @php
                                                    $dados = $funcionamento[$dia] ?? ['abertura' => null, 'fechamento' => null, 'funciona' => false];
                                                @endphp
                                                <tr>
                                                    <td>{{ $dia }}</td>
                                                    <td>
                                                        <input type="time" class="form-control" name="funcionamento[{{ $dia }}][abertura]"
                                                            value="{{ $dados['abertura'] }}"
                                                            id="abertura-{{ strtolower($dia) }}"
                                                            {{ !$dados['funciona'] ? 'disabled' : '' }}>
                                                    </td>
                                                    <td>
                                                        <input type="time" class="form-control" name="funcionamento[{{ $dia }}][fechamento]"
                                                            value="{{ $dados['fechamento'] }}"
                                                            id="fechamento-{{ strtolower($dia) }}"
                                                            {{ !$dados['funciona'] ? 'disabled' : '' }}>
                                                    </td>
                                                    <td class="text-center">
                                                        <input type="checkbox" class="form-check-input" name="funcionamento[{{ $dia }}][funciona]"
                                                            id="fechado-{{ strtolower($dia) }}"
                                                            value="true" {{ !$dados['funciona'] ? 'checked' : '' }}
                                                            onchange="toggleDay('{{ strtolower($dia) }}')">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div id="doacoes" class="content-section">
                        <form class="card-body" id="form-menu-doacoes" action="{{ route('instituicao.update', $instituicao->id_instituicao) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <h2 class="mb-4">Doações</h2>
                            <h3 class="card-title">Crie as categorias das doações que poderão ser recebidas</h3>
                            <input type="hidden" name="menu" value="5">
                            <div class="row mt-4">
                                <div class="col-md-6 col-lg-4">
                                    <input type="text" name="categoria" class="form-control" value="Roupas">
                                </div>
                            </div>
                            <h3 class="card-title mt-4">Categorias disponíveis para solicitações</h3>
                            @foreach ($categorias as $categoria)
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox">
                                    <span class="form-check-label">{{ $categoria->descricao_categoria }}</span>
                                </label>
                            @endforeach
                        </form>
                    </div>
                    <div id="voluntariado" class="content-section">
                        <form class="card-body" id="form-menu-voluntariado" action="{{ route('instituicao.update', $instituicao->id_instituicao) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <h2 class="mb-4">Voluntariado</h2>
                            <h3 class="card-title">Habilidades disponíveis para solicitações</h3>
                            <input type="hidden" name="menu" value="6">
                            @foreach($habilidades as $habilidade)
                                <label class="form-check">
                                    <input class="form-check-input" type="checkbox" name="habilidades[]"
                                        value="{{ $habilidade->id_habilidade }}">
                                    <span class="form-check-label">{{ $habilidade->descricao_habilidade }}</span>
                                </label>
                            @endforeach
                        </form>
                    </div>
                    <div class="card-footer bg-transparent mt-auto">
                        <div class="btn-list justify-content-end">
                            <a href="#" class="btn">Cancelar</a>
                            <a href="#" class="btn btn-primary" onclick="showModal()">Enviar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('modal')
<div class="modal modal-blur fade" id="modal-danger" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-status bg-danger"></div>
            <div class="modal-body text-center py-4">
                <!-- Download SVG icon from http://tabler-icons.io/i/alert-triangle -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-danger icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.24 3.957l-8.422 14.06a1.989 1.989 0 0 0 1.7 2.983h16.845a1.989 1.989 0 0 0 1.7 -2.983l-8.423 -14.06a1.989 1.989 0 0 0 -3.4 0z" /><path d="M12 9v4" /><path d="M12 17h.01" /></svg>
                <h3>Tem certeza?</h3>
                <div class="text-secondary">Deseja realmente remover esse telefone?</div>
            </div>
            <div class="modal-footer">
                <div class="w-100">
                    <div class="row">
                        <div class="col">
                            <a href="#" class="btn w-100" data-bs-dismiss="modal">
                                Cancelar
                            </a>
                        </div>
                        <div class="col">
                            <a href="#" class="btn btn-danger w-100" data-bs-dismiss="modal">
                                Remover
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal modal-blur fade" id="modal-senha" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <!-- Formulário para alterar a senha -->
            <form action="{{ route('instituicao.update', $instituicao->id_instituicao) }}" method="POST" id="form-update-password">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Alterar Senha</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Input para a senha atual -->
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Senha Atual</label>
                        <input type="password" id="current_password" name="current_password" placeholder="Insira a senha atual" class="form-control" required>
                    </div>

                    <!-- Input para a nova senha -->
                    <div class="mb-3">
                        <label for="new_password" class="form-label">Nova Senha</label>
                        <input type="password" id="new_password" name="new_password" placeholder="Insira a nova senha" class="form-control" required>
                    </div>

                    <!-- Confirmação da nova senha -->
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirmar Nova Senha</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Confirme a nova senha" required>
                    </div>

                    <input type="hidden" name="menu" value="7" class="form-control" required>

                    <!-- Mensagem de erro -->
                    <div id="password-error" class="text-danger" style="display: none;">As senhas não coincidem!</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary" id="submit-btn">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal modal-blur fade" id="modal-envia" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div id="modal-title" class="modal-title">Atualizar dados do perfil</div>
                <div>Tem certeza dos dados preenchidos?</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Cancelar</button>
                <button id="confirmButton" class="btn btn-primary">Sim</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    function showSection(sectionId) {
        // Remove active class from all menu items
        document.querySelectorAll('.list-group-item').forEach(item => item.classList.remove('active'));
        // Add active class to the clicked menu item
        document.getElementById('menu-' + sectionId).classList.add('active');

        // Hide all content sections
        document.querySelectorAll('.content-section').forEach(section => section.classList.remove('active'));
        // Show the selected content section
        document.getElementById(sectionId).classList.add('active');
    }
</script>
<script>
    document.getElementById('change-photo').addEventListener('click', function(event) {
        event.preventDefault();  // Evita comportamento padrão do link
        document.getElementById('upload').click();  // Aciona o campo de upload
    });
</script>
<script>
    document.getElementById('telefone').addEventListener('input', function (e) {
        let x = e.target.value.replace(/\D/g, '');
        x = x.replace(/^(\d{2})(\d)/, '($1) $2');
        x = x.replace(/(\d)(\d{4})$/, '$1-$2');
        e.target.value = x;
    });
    document.getElementById('whatsapp').addEventListener('input', function (e) {
        let x = e.target.value.replace(/\D/g, '');
        x = x.replace(/^(\d{2})(\d)/, '($1) $2');
        x = x.replace(/(\d)(\d{4})$/, '$1-$2');
        e.target.value = x;
    });
</script>
<script>
    document.getElementById('form-update-password').addEventListener('submit', function(event) {
        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = document.getElementById('confirm_password').value;

        if (newPassword !== confirmPassword) {
            event.preventDefault(); // Impede o envio do formulário
            document.getElementById('password-error').style.display = 'block';
        } else {
            document.getElementById('password-error').style.display = 'none';
        }
    });
</script>
<script>
    function toggleDay(dia) {
        const checkbox = document.getElementById('fechado-' + dia);
        const abertura = document.getElementById('abertura-' + dia);
        const fechamento = document.getElementById('fechamento-' + dia);

        if (checkbox.checked) {
            abertura.disabled = true;
            fechamento.disabled = true;
            checkbox.value = false;
        } else {
            abertura.disabled = false;
            fechamento.disabled = false;
            checkbox.value = true;
        }
    }
</script>
<script>
    document.getElementById('confirmButton').onclick = function() {
        const activeMenu = document.querySelector('.menu-item.active');
        const form = document.getElementById('form-'+activeMenu.id);

        // Optionally validate or process form data here
        form.submit();
    };

</script>
<script>
    function showModal() {
        // Encontrar o item de menu ativo
        const activeMenuItem = document.querySelector('.list-group-item.active');

        // Pegar o texto do menu ativo
        const activeMenuText = activeMenuItem ? activeMenuItem.textContent.trim() : 'menu ativado';

        // Atualizar o título do modal com o nome do menu ativo
        document.getElementById('modal-title').textContent = `Atualizar dados do menu ${activeMenuText}`;

        // Exibir o modal
        const modal = new bootstrap.Modal(document.getElementById('modal-envia'));
        modal.show();

        // Definir qual formulário será enviado com base no menu ativo
        document.getElementById('confirmButton').onclick = function() {
            submitForm(activeMenuText.toLowerCase()); // Envia o formulário correto
        };
    }

    function submitForm(menu){
        // Verificar qual menu foi passado e submeter o formulário correspondente
        switch(menu) {
            case 'conta':
                document.getElementById('form-menu-conta').submit();
                break;
            case 'endereço':
                document.getElementById('form-menu-endereco').submit();
                break;
            case 'contato':
                document.getElementById('form-menu-contato').submit();
                break;
            case 'funcionamento':
                document.getElementById('form-menu-funcionamento').submit();
                break;
            case 'doações':
                document.getElementById('form-menu-doacoes').submit();
                break;
            case 'voluntariado':
                document.getElementById('form-menu-voluntariado').submit();
                break;
            default:
                console.log("Formulário não encontrado!");
                break;
        }
    }
</script>
@endsection
