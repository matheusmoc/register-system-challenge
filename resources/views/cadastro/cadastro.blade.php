<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cadastro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="/css/cadastro.css">
</head>
<body>
    <div id="app">
        <!-- Header -->
        <div class="container py-5">
            <div class="row justify-content-center text-center mb-5">
                <div class="col-lg-8">
                    <h1 class="display-4 fw-bold text-white mb-3">
                        <i class="fas fa-user-cog me-3"></i>
                        <span class="gradient-text">Cadastro</span>
                    </h1>
                    <p class="lead text-white-50">
                        Sistema de cadastro
                    </p>
                </div>
            </div>

            <!-- Main Content -->
            <div class="row justify-content-center">
                <!-- Form Section -->
                <div class="col-lg-10">
                    <div class="glass-morphism p-4 mb-5 neon-glow">
                        <div class="row align-items-center mb-4">
                            <div class="col">
                                <h4 class="text-dark mb-0">
                                    <i class="fas" :class="editing ? 'fa-edit text-warning' : 'fa-plus-circle text-primary'"></i>
                                    (( editing ? 'Editando Cadastro' : 'Novo Cadastro' ))
                                </h4>
                            </div>
                            <div class="col-auto">
                                <span class="badge bg-primary rounded-pill">(( registros.length )) cadastros</span>
                            </div>
                        </div>

                        <form @submit.prevent="salvarCadastro" enctype="multipart/form-data">
                            <div class="row g-3">
                                <!-- Nome -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nome Completo *</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <input type="text" class="form-control form-control-modern" 
                                               v-model="form.nome" placeholder="Digite o nome completo">
                                    </div>
                                    <small class="text-danger" v-if="errors.nome">(( errors.nome ))</small>
                                </div>

                                <!-- Idade -->
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Idade *</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-birthday-cake"></i></span>
                                        <input type="number" class="form-control form-control-modern" 
                                               v-model="form.idade" min="1" placeholder="Idade">
                                    </div>
                                    <small class="text-danger" v-if="errors.idade">(( errors.idade ))</small>
                                </div>

                                <!-- Sexo -->
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Sexo *</label>
                                    <select class="form-select form-control-modern" v-model="form.sexo">
                                        <option value="">Selecione...</option>
                                        <option value="Masculino">Masculino</option>
                                        <option value="Feminino">Feminino</option>
                                        <option value="Outro">Outro</option>
                                    </select>
                                    <small class="text-danger" v-if="errors.sexo">(( errors.sexo ))</small>
                                </div>

                                <!-- CEP -->
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">CEP *</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                        <input type="text" class="form-control form-control-modern" 
                                               v-model="form.cep" v-mask="'##.###-###'" 
                                               @blur="buscarCEP" placeholder="00.000-000">
                                    </div>
                                    <small class="text-danger" v-if="errors.cep">(( errors.cep ))</small>
                                </div>

                                <!-- Cidade -->
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Cidade *</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-city"></i></span>
                                        <input type="text" class="form-control form-control-modern" 
                                               v-model="form.cidade" placeholder="Cidade">
                                    </div>
                                    <small class="text-danger" v-if="errors.cidade">(( errors.cidade ))</small>
                                </div>

                                <!-- Estado -->
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">Estado *</label>
                                    <input type="text" class="form-control form-control-modern text-uppercase" 
                                           v-model="form.estado" maxlength="2" placeholder="UF">
                                    <small class="text-danger" v-if="errors.estado">(( errors.estado ))</small>
                                </div>

                                <!-- Salário -->
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Salário *</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
                                        <input type="text" class="form-control form-control-modern" 
                                               v-model="form.salario" v-money="moneyConfig"
                                               placeholder="1.234,56">
                                    </div>
                                    <small class="text-danger" v-if="errors.salario">(( errors.salario ))</small>
                                </div>

                                <!-- Rua -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Rua *</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-road"></i></span>
                                        <input type="text" class="form-control form-control-modern" 
                                               v-model="form.rua" placeholder="Nome da rua">
                                    </div>
                                    <small class="text-danger" v-if="errors.rua">(( errors.rua ))</small>
                                </div>

                                <!-- Bairro -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Bairro *</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-map"></i></span>
                                        <input type="text" class="form-control form-control-modern" 
                                               v-model="form.bairro" placeholder="Bairro">
                                    </div>
                                    <small class="text-danger" v-if="errors.bairro">(( errors.bairro ))</small>
                                </div>

                                <!-- Ensino Médio -->
                                <div class="col-md-6">
                                    <div class="custom-checkbox form-check form-switch">
                                        <input class="form-check-input" type="checkbox" 
                                               v-model="form.ensino_medio" id="ensino_medio">
                                        <label class="form-check-label fw-semibold" for="ensino_medio">
                                            <i class="fas fa-graduation-cap me-2"></i>
                                            Possui Ensino Médio Completo
                                        </label>
                                    </div>
                                </div>

                                <!-- Anexo -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Anexo (( editing ? '' : '*' ))</label>
                                    <div class="file-upload-area" 
                                         @dragover="dragover" 
                                         @drop="drop"
                                         @click="$refs.anexo.click()"
                                         :class="{ 'dragover': isDragging }">
                                        <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-3"></i>
                                        <p class="mb-2">Arraste o arquivo ou clique para selecionar</p>
                                        <small class="text-muted">PDF, JPG, PNG (Máx. 10MB)</small>
                                        <input type="file" ref="anexo" @change="onFileChange" 
                                               accept=".pdf,.jpg,.png" class="d-none">
                                        <div v-if="form.anexo_nome" class="mt-2">
                                            <span class="badge bg-success">
                                                <i class="fas fa-file me-1"></i>(( form.anexo_nome ))
                                            </span>
                                        </div>
                                    </div>
                                    <small class="text-danger" v-if="errors.anexo">(( errors.anexo ))</small>
                                </div>
                            </div>

                            <!-- Botões -->
                            <div class="row mt-4">
                                <div class="col-12 text-end">
                                    <button type="button" class="btn btn-outline-secondary me-2" 
                                            @click="cancelarEdicao" v-if="editing">
                                        <i class="fas fa-times me-1"></i>Cancelar
                                    </button>
                                    <button type="submit" class="btn btn-neon" :disabled="loading">
                                        <i class="fas" :class="loading ? 'fa-spinner fa-spin' : (editing ? 'fa-save' : 'fa-plus')"></i>
                                        (( editing ? (loading ? 'Salvando...' : 'Atualizar') : (loading ? 'Cadastrando...' : 'Cadastrar') ))
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Navigation Tabs -->
                <div class="col-12">
                    <ul class="nav nav-pills nav-pills-custom justify-content-center mb-4">
                        <li class="nav-item">
                            <a class="nav-link" :class="{ 'active': abaAtiva === 'lista' }" 
                               @click="abaAtiva = 'lista'">
                                <i class="fas fa-list me-2"></i>Lista de Cadastros
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" :class="{ 'active': abaAtiva === 'dashboard' }" 
                               @click="abaAtiva = 'dashboard'">
                                <i class="fas fa-chart-bar me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" :class="{ 'active': abaAtiva === 'graficos' }" 
                               @click="abaAtiva = 'graficos'">
                                <i class="fas fa-chart-pie me-2"></i>Gráficos
                            </a>
                        </li>
                    </ul>

                    <transition name="fade" mode="out-in">
                        <!-- Lista de Cadastros -->
                        <div v-if="abaAtiva === 'lista'" key="lista" class="row">
                            <div class="col-12 mb-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h4 class="text-white">
                                        <i class="fas fa-users me-2"></i>Cadastros Realizados
                                    </h4>
                                    <div>
                                        <button class="btn btn-outline-light btn-sm me-2" @click="carregarRegistros">
                                            <i class="fas fa-sync-alt" :class="{ 'fa-spin': carregando }"></i>
                                        </button>
                                        <div class="btn-group">
                                            <button class="btn btn-outline-light btn-sm" 
                                                    @click="ordenarPor = 'nome'">
                                                Nome (( ordenarPor === 'nome' ? '▼' : '' ))
                                            </button>
                                            <button class="btn btn-outline-light btn-sm" 
                                                    @click="ordenarPor = 'idade'">
                                                Idade (( ordenarPor === 'idade' ? '▼' : '' ))
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-6 mb-4" v-for="registro in registrosOrdenados" :key="registro.id">
                                <div class="card floating-card h-100">
                                    <div class="card-header bg-transparent border-bottom-0 pt-3">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <h6 class="card-title mb-1 text-truncate">(( registro.nome ))</h6>
                                            <span class="badge-modern" 
                                                  :class="{
                                                      'bg-primary': registro.sexo === 'Masculino',
                                                      'bg-danger': registro.sexo === 'Feminino', 
                                                      'bg-success': registro.sexo === 'Outro'
                                                  }">
                                                (( registro.sexo ))
                                            </span>
                                        </div>
                                        <p class="text-muted small mb-0">
                                            <i class="fas fa-birthday-cake me-1"></i>(( registro.idade )) anos
                                        </p>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-2">
                                            <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                            <small>(( registro.cidade ))/(( registro.estado ))</small>
                                        </div>
                                        <div class="mb-2">
                                            <i class="fas fa-graduation-cap" 
                                               :class="registro.ensino_medio ? 'text-success' : 'text-warning'"
                                               :title="registro.ensino_medio ? 'Ensino Médio Completo' : 'Ensino Médio Incompleto'">
                                            </i>
                                            <small class="ms-2">(( registro.ensino_medio ? 'Ensino Médio Completo' : 'Ensino Médio Incompleto' ))</small>
                                        </div>
                                        <div class="mb-2">
                                            <i class="fas fa-money-bill-wave text-success me-2"></i>
                                            <small>R$ (( formatarSalario(registro.salario) ))</small>
                                        </div>
                                            <div>
                                                <i class="fas fa-file text-info me-2"></i>
                                                <small class="text-truncate">
                                                    <span v-if="registro.anexo">
                                                        <!-- Mostra o nome e link para download -->
                                                        <a :href="'/storage/' + registro.anexo" 
                                                        target="_blank" 
                                                        class="text-decoration-none"
                                                        :title="registro.anexo_nome">
                                                            (( registro.anexo_nome ))
                                                        </a>
                                                    </span>
                                                    <span v-else class="text-muted">
                                                        Sem anexo
                                                    </span>
                                                </small>
                                            </div>
                                    </div>
                                    <div class="card-footer bg-transparent border-top-0">
                                        <div class="btn-group w-100">
                                            <button class="btn btn-outline-primary btn-sm" 
                                                    @click="editarRegistro(registro.id)">
                                                <i class="fas fa-edit me-1"></i>Editar
                                            </button>
                                            <button class="btn btn-outline-danger btn-sm" 
                                                    @click="confirmarExclusao(registro)">
                                                <i class="fas fa-trash me-1"></i>Excluir
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Dashboard -->
                        <div v-if="abaAtiva === 'dashboard'" key="dashboard" class="row">
                            <div class="col-12 mb-4">
                                <h4 class="text-white text-center">
                                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard de Estatísticas
                                </h4>
                            </div>
                            
                            <!-- Stats Cards -->
                            <div class="col-xl-3 col-md-6 mb-4" v-for="stat in estatisticas" :key="stat.titulo">
                                <div class="stats-card pulse-animation">
                                    <div class="stat-icon mb-3">
                                        <i :class="stat.icone" class="fa-2x"></i>
                                    </div>
                                    <h3 class="stat-number">(( stat.valor ))</h3>
                                    <p class="stat-label mb-0">(( stat.titulo ))</p>
                                </div>
                            </div>

                            <!-- Informações Adicionais -->
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <div class="glass-morphism p-4">
                                            <h5 class="text-dark mb-3">
                                                <i class="fas fa-venus-mars me-2"></i>Distribuição por Sexo
                                            </h5>
                                            <div v-for="dist in distribuicaoSexo" :key="dist.sexo" 
                                                 class="d-flex justify-content-between align-items-center mb-2">
                                                <span>(( dist.sexo ))</span>
                                                <div class="d-flex align-items-center">
                                                    <div class="progress flex-grow-1 mx-2" style="width: 100px; height: 8px">
                                                        <div class="progress-bar" :class="dist.cor" 
                                                             :style="{ width: dist.percentual + '%' }"></div>
                                                    </div>
                                                    <small class="text-muted">(( dist.quantidade )) (( dist.percentual ))</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <div class="glass-morphism p-4">
                                            <h5 class="text-dark mb-3">
                                                <i class="fas fa-graduation-cap me-2"></i>Nível de Ensino
                                            </h5>
                                            <div class="text-center">
                                                <div class="display-4 fw-bold text-primary">
                                                    (( percentualEnsinoMedio ))%
                                                </div>
                                                <p class="text-muted mb-0">com Ensino Médio Completo</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Gráficos -->
                        <div v-if="abaAtiva === 'graficos'" key="graficos" class="row">
                            <div class="col-12 mb-4">
                                <h4 class="text-white text-center">
                                    <i class="fas fa-chart-pie me-2"></i>Análise Gráfica
                                </h4>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="glass-morphism p-4">
                                    <h5 class="text-dark mb-3">Distribuição por Sexo</h5>
                                    <div class="chart-container">
                                        <canvas id="sexoChart"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="glass-morphism p-4">
                                    <h5 class="text-dark mb-3">Nível de Ensino</h5>
                                    <div class="chart-container">
                                        <canvas id="ensinoChart"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="glass-morphism p-4">
                                    <h5 class="text-dark mb-3">Distribuição por Idade</h5>
                                    <div class="chart-container">
                                        <canvas id="idadeChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </transition>
                </div>
            </div>
        </div>

        <!-- Modal de Confirmação -->
        <div class="modal fade" id="confirmModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                            Confirmação
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Tem certeza que deseja excluir o registro de <strong>(( registroSelecionado.nome ))</strong>?</p>
                        <small class="text-muted">Esta ação não pode ser desfeita.</small>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Cancelar
                        </button>
                        <button type="button" class="btn btn-danger" @click="excluirRegistro" :disabled="loading">
                            <i class="fas fa-trash me-1"></i>
                            (( loading ? 'Excluindo...' : 'Confirmar Exclusão' ))
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Toast de Notificação -->
        <div class="toast-container position-fixed top-0 end-0 p-3">
            <div class="toast align-items-center text-white bg-success border-0" 
                 :class="{ 'show': toast.mostrar }" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        <i :class="toast.icone" class="me-2"></i>(( toast.mensagem ))
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" 
                            @click="toast.mostrar = false"></button>
                </div>
            </div>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue@2.7.16/dist/vue.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue-the-mask@0.11.1/dist/vue-the-mask.min.js"></script>
<script src="https://unpkg.com/v-money@0.8.1/dist/v-money.js"></script>
<script src="/js/cadastro.js"></script>

</body>
</html>