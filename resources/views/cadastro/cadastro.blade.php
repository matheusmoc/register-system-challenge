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
    {{-- <link rel="stylesheet" href="{{ asset('css/cadastro.css') }}"> --}}
    <style>
                :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --secondary: #10b981;
            --accent: #f59e0b;
            --dark: #1e293b;
            --light: #f8fafc;
            --glass: rgba(255, 255, 255, 0.25);
        }

        body {
            background: linear-gradient(135deg, #1e293b 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
        }

        .neon-glow {
            box-shadow: 0 0 20px rgba(99, 102, 241, 0.3);
        }

        .glass-morphism {
            background: var(--glass);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
        }

        .floating-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            border-radius: 16px;
            overflow: hidden;
        }

        .floating-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .gradient-text {
            background: linear-gradient(135deg, var(--accent), var(--primary-dark));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .btn-neon {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-neon::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn-neon:hover::before {
            left: 100%;
        }

        .btn-neon:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4);
        }

        .form-control-modern {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 16px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
        }

        .form-control-modern:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
            background: white;
        }

        .nav-pills-custom .nav-link {
            border-radius: 50px;
            padding: 12px 24px;
            margin: 0 8px;
            color: var(--dark);
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .nav-pills-custom .nav-link.active {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
        }

        .stats-card {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border-radius: 16px;
            padding: 25px;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-5px);
        }

        .pulse-animation {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .fade-enter-active, .fade-leave-active {
            transition: opacity 0.5s, transform 0.5s;
        }
        .fade-enter, .fade-leave-to {
            opacity: 0;
            transform: translateY(20px);
        }

        .badge-modern {
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
        }

        .file-upload-area {
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
        }

        .file-upload-area.dragover {
            border-color: var(--primary);
            background: rgba(99, 102, 241, 0.1);
        }

        .custom-checkbox .form-check-input {
            width: 20px;
            height: 20px;
            border-radius: 6px;
            border: 2px solid #cbd5e1;
        }

        .custom-checkbox .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }
    </style>
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
{{-- <script src="{{ asset('js/cadastro.js') }}"></script> --}}

<script>
Vue.use(VueTheMask);
Vue.directive('money', {
    bind: function (el, binding, vnode) {
        const config = Object.assign({
            decimal: ',',
            thousands: '.',
            prefix: '',
            suffix: '',
            precision: 2,
            masked: false
        }, binding.value || {});

        el.addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, '');
            
            if (value === '') {
                e.target.value = '';
                vnode.context.$set(vnode.context.form, 'salario', '');
                return;
            }
            
            value = (parseInt(value) / Math.pow(10, config.precision)).toFixed(config.precision);
            value = value.replace('.', config.decimal);
            
            const parts = value.split(config.decimal);
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, config.thousands);
            
            const formattedValue = config.prefix + parts.join(config.decimal) + config.suffix;
            e.target.value = formattedValue;
            vnode.context.$set(vnode.context.form, 'salario', formattedValue);
        });
    }
});

const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';


new Vue({
    el: '#app',
    delimiters: ['((', '))'],
    data: {
        abaAtiva: 'lista',
        registros: [],
        form: {
            id: null,
            nome: '',
            idade: '',
            cep: '',
            cidade: '',
            estado: '',
            rua: '',
            bairro: '',
            ensino_medio: false,
            sexo: '',
            salario: '',
            anexo: null,
            anexo_nome: ''
        },
        moneyConfig: {
            decimal: ',',
            thousands: '.',
            prefix: '',
            suffix: '',
            precision: 2,
            masked: false
        },
        errors: {},
        editing: false,
        loading: false,
        carregando: false,
        isDragging: false,
        registroSelecionado: {},
        ordenarPor: 'nome',
        toast: {
            mostrar: false,
            mensagem: '',
            icone: ''
        },

        graficos: {
            sexo: null,
            ensino: null,
            idade: null
        }
    },
    computed: {
        registrosOrdenados() {
            return [...this.registros].sort((a, b) => {
                if (this.ordenarPor === 'nome') {
                    return a.nome.localeCompare(b.nome);
                } else if (this.ordenarPor === 'idade') {
                    return b.idade - a.idade;
                }
                return 0;
            });
        },
        estatisticas() {
            return [
                {
                    titulo: 'Total de Cadastros',
                    valor: this.registros.length,
                    icone: 'fas fa-users'
                },
                {
                    titulo: 'Com Ensino Médio',
                    valor: this.registros.filter(r => r.ensino_medio).length,
                    icone: 'fas fa-graduation-cap'
                },
                {
                    titulo: 'Idade Média',
                    valor: this.calcularIdadeMedia,
                    icone: 'fas fa-birthday-cake'
                },
                {
                    titulo: 'Salário Médio',
                    valor: 'R$ ' + this.calcularSalarioMedio,
                    icone: 'fas fa-money-bill-wave'
                }
            ];
        },
        distribuicaoSexo() {
            const total = this.registros.length;
            if (total === 0) return [];
            
            const sexos = ['Masculino', 'Feminino', 'Outro'];
            const cores = ['bg-primary', 'bg-danger', 'bg-success'];
            
            return sexos.map((sexo, index) => {
                const quantidade = this.registros.filter(r => r.sexo === sexo).length;
                const percentual = total > 0 ? ((quantidade / total) * 100).toFixed(1) : 0;
                
                return {
                    sexo,
                    quantidade,
                    percentual,
                    cor: cores[index]
                };
            });
        },
        percentualEnsinoMedio() {
            if (this.registros.length === 0) return 0;
            const comEnsino = this.registros.filter(r => r.ensino_medio).length;
            return ((comEnsino / this.registros.length) * 100).toFixed(1);
        },
        calcularIdadeMedia() {
            if (this.registros.length === 0) return 0;
            const total = this.registros.reduce((sum, r) => sum + parseInt(r.idade), 0);
            return (total / this.registros.length).toFixed(1);
        },
        calcularSalarioMedio() {
            if (this.registros.length === 0) return '0,00';
            const total = this.registros.reduce((sum, r) => sum + parseFloat(r.salario), 0);
            return (total / this.registros.length).toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }
    },
    mounted() {
        this.carregarRegistros();
        this.inicializarMascaras();
    },
    methods: {
        inicializarMascaras() {
            // Máscara para CEP
            const cepInput = document.querySelector('input[v-model="form.cep"]');
            if (cepInput) {
                cepInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    if (value.length > 5) {
                        value = value.substring(0,5) + '.' + value.substring(5,8) + '-' + value.substring(8,11);
                    } else if (value.length > 2) {
                        value = value.substring(0,2) + '.' + value.substring(2,5);
                    }
                    e.target.value = value;
                });
            }

            // Máscara para salário
            const salarioInput = document.querySelector('input[v-model="form.salario"]');
            if (salarioInput) {
                salarioInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    if (value === '') return;
                    
                    value = (parseInt(value) / 100).toFixed(2);
                    value = value.replace('.', ',');
                    
                    const parts = value.split(',');
                    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    
                    e.target.value = parts.join(',');
                });
            }
        },

        async carregarRegistros() {
            this.carregando = true;
            try {
                const response = await fetch('/cadastro/listar');
                const data = await response.json();
                
                if (data.success) {
                    this.registros = data.data.map(registro => ({
                        ...registro,
                        anexo_nome: registro.anexo ? this.extrairNomeArquivo(registro.anexo) : ''
                    }));
                    this.mostrarToast('Cadastros carregados com sucesso!', 'fas fa-check');
                } else {
                    throw new Error(data.message || 'Erro ao carregar registros');
                }
            } catch (error) {
                console.error('Erro ao carregar registros:', error);
                this.mostrarToast('Erro ao carregar cadastros: ' + error.message, 'fas fa-exclamation-triangle', 'error');
            } finally {
                this.carregando = false;
            }
        },

        async salvarCadastro() {
            this.loading = true;
            this.errors = {};
            
            try {
                if (!this.form.nome || this.form.nome.trim() === '') this.errors.nome = 'Nome é obrigatório';
                if (!this.form.idade) this.errors.idade = 'Idade é obrigatória';
                if (!this.form.sexo) this.errors.sexo = 'Sexo é obrigatório';
                if (!this.form.cep) this.errors.cep = 'CEP é obrigatório';
                if (!this.form.cidade) this.errors.cidade = 'Cidade é obrigatória';
                if (!this.form.estado) this.errors.estado = 'Estado é obrigatório';
                if (!this.form.rua) this.errors.rua = 'Rua é obrigatória';
                if (!this.form.bairro) this.errors.bairro = 'Bairro é obrigatório';
                if (!this.form.salario) this.errors.salario = 'Salário é obrigatório';
                
                if (Object.keys(this.errors).length > 0) {
                    throw new Error('Preencha todos os campos obrigatórios');
                }
                
                const formData = new FormData();
                
                console.log('=== ENVIANDO DADOS ===');
                console.log('Editando:', this.editing);
                console.log('ID:', this.form.id);
                console.log('Dados do formulário:', this.form);
                
                Object.keys(this.form).forEach(key => {
                    if (key === 'anexo_nome') return;

                    const value = this.form[key];
                    let valueToSend = value;
                    
                    if (value === null || value === undefined) {
                        valueToSend = '';
                    }
                    
                    if (key === 'anexo') {
                        if (value instanceof File) {
                            formData.append('anexo', value);
                            console.log('Adicionado anexo (arquivo):', value.name);
                        }

                    } else if (key === 'ensino_medio') {
                        formData.append('ensino_medio', value ? '1' : '0');
                        console.log('Adicionado ensino_medio:', value ? '1' : '0');
                    } else if (key === 'id' && this.editing) {
                        // Incluir ID apenas quando estiver editando
                        formData.append('_method', 'PUT'); 
                        console.log('Adicionado ID para edição:', value);
                    } else {
                        // Campos normais
                        formData.append(key, valueToSend);
                        console.log(`Adicionado ${key}:`, valueToSend);
                    }
                });
                
                console.log('=== CONTEÚDO DO FORMDATA ===');
                for (let pair of formData.entries()) {
                    console.log(pair[0] + ':', pair[1]);
                }
                
                const url = this.editing ? `/cadastro/${this.form.id}` : '/cadastro';
                const method = this.editing ? 'POST' : 'POST';
                
                console.log('URL:', url);
                console.log('Método:', method);
                
                const response = await fetch(url, {
                    method: method,
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const data = await response.json();
                console.log('Resposta do servidor:', data);
                
                if (data.success) {
                    this.mostrarToast(data.message, 'fas fa-check');
                    this.limparFormulario();
                    this.carregarRegistros(); 
                } else {
                    throw new Error(data.message || 'Erro ao salvar cadastro');
                }
                
            } catch (error) {
                console.error('Erro:', error);
                this.mostrarToast(error.message, 'fas fa-exclamation-triangle', 'error');
            } finally {
                this.loading = false;
            }
        },
        
        extrairNomeArquivo(caminho) {
            if (!caminho) return '';
            return caminho.split('/').pop();
        },
        async editarRegistro(id) {
            try {
                const response = await fetch(`/cadastro/${id}`);
                const data = await response.json();
                
                if (data.success) {
                    this.form = { 
                        ...data.data,
                        ensino_medio: Boolean(data.data.ensino_medio),
                        anexo_nome: data.data.anexo ? this.extrairNomeArquivo(data.data.anexo) : ''
                    };
                    this.editing = true;
                    this.abaAtiva = 'lista';
                    
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                    throw new Error(data.message || 'Erro ao carregar registro');
                }
            } catch (error) {
                console.error('Erro ao carregar registro:', error);
                this.mostrarToast('Erro ao carregar registro: ' + error.message, 'fas fa-exclamation-triangle', 'error');
            }
        },
        
        confirmarExclusao(registro) {
            this.registroSelecionado = registro;
            new bootstrap.Modal(document.getElementById('confirmModal')).show();
        },
        
        async excluirRegistro() {
            this.loading = true;
            
            try {
                const response = await fetch(`/cadastro/${this.registroSelecionado.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.mostrarToast(data.message, 'fas fa-trash');
                    this.carregarRegistros();
                    bootstrap.Modal.getInstance(document.getElementById('confirmModal')).hide();
                } else {
                    throw new Error(data.message || 'Erro ao excluir registro');
                }
                
            } catch (error) {
                console.error('Erro ao excluir:', error);
                this.mostrarToast('Erro ao excluir registro: ' + error.message, 'fas fa-exclamation-triangle', 'error');
            } finally {
                this.loading = false;
            }
        },
        
        cancelarEdicao() {
            this.limparFormulario();
        },
        
        limparFormulario() {
            this.form = {
                id: null,
                nome: '',
                idade: '',
                cep: '',
                cidade: '',
                estado: '',
                rua: '',
                bairro: '',
                ensino_medio: false,
                sexo: '',
                salario: '',
                anexo: null,
                anexo_nome: ''
            };
            this.errors = {};
            this.editing = false;
            if (this.$refs.anexo) {
                this.$refs.anexo.value = '';
            }
        },
        
        async buscarCEP() {
            const cep = this.form.cep.replace(/\D/g, '');
            if (cep.length === 8) {
                try {
                    const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
                    const data = await response.json();
                    
                    if (!data.erro) {
                        this.form.rua = data.logradouro;
                        this.form.bairro = data.bairro;
                        this.form.cidade = data.localidade;
                        this.form.estado = data.uf;
                    }
                } catch (error) {
                    console.error('Erro ao buscar CEP:', error);
                }
            }
        },
        
        onFileChange(event) {
            const file = event.target.files[0];
            if (file) {
                if (file.size > 10 * 1024 * 1024) {
                    this.errors.anexo = 'Arquivo muito grande. Máximo 10MB.';
                    return;
                }
                
                const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
                if (!allowedTypes.includes(file.type)) {
                    this.errors.anexo = 'Tipo de arquivo não permitido. Use PDF, JPG ou PNG.';
                    return;
                }
                
                this.form.anexo = file;
                this.form.anexo_nome = file.name;
                this.errors.anexo = '';
            }
        },
        
        dragover(event) {
            event.preventDefault();
            this.isDragging = true;
        },
        
        drop(event) {
            event.preventDefault();
            this.isDragging = false;
            
            const files = event.dataTransfer.files;
            if (files.length > 0) {
                this.$refs.anexo.files = files;
                this.onFileChange({ target: { files: files } });
            }
        },
        
        formatarSalario(salario) {
            return parseFloat(salario).toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        },
        
        mostrarToast(mensagem, icone, tipo = 'success') {
            this.toast.mensagem = mensagem;
            this.toast.icone = icone;
            this.toast.mostrar = true;
            
            setTimeout(() => {
                this.toast.mostrar = false;
            }, 4000);
        },
        
        async carregarEstatisticas() {
            try {
                const response = await fetch('/cadastro/estatisticas');
                const data = await response.json();
                
                if (data.success) {
                    console.log('Estatísticas carregadas:', data.data);
                }
            } catch (error) {
                console.error('Erro ao carregar estatísticas:', error);
            }
        },
        
        destruirGraficos() {
            // Destruir gráficos existentes antes de criar novos
            if (this.graficos.sexo) {
                this.graficos.sexo.destroy();
                this.graficos.sexo = null;
            }
            if (this.graficos.ensino) {
                this.graficos.ensino.destroy();
                this.graficos.ensino = null;
            }
            if (this.graficos.idade) {
                this.graficos.idade.destroy();
                this.graficos.idade = null;
            }
        },
        
        inicializarGraficos() {
            this.destruirGraficos();
            
            this.$nextTick(() => {
                setTimeout(() => {

                    // Gráfico de Sexo
                    const sexoCtx = document.getElementById('sexoChart');
                    if (sexoCtx) {
    
                        const rect = sexoCtx.getBoundingClientRect();
                        if (rect.width > 0 && rect.height > 0) {
                            this.graficos.sexo = new Chart(sexoCtx, {
                                type: 'doughnut',
                                data: {
                                    labels: ['Masculino', 'Feminino', 'Outro'],
                                    datasets: [{
                                        data: [
                                            this.registros.filter(r => r.sexo === 'Masculino').length,
                                            this.registros.filter(r => r.sexo === 'Feminino').length,
                                            this.registros.filter(r => r.sexo === 'Outro').length
                                        ],
                                        backgroundColor: ['#4361ee', '#f72585', '#4cc9f0'],
                                        borderWidth: 2,
                                        borderColor: '#fff'
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: { 
                                            position: 'bottom',
                                            labels: {
                                                padding: 20,
                                                usePointStyle: true,
                                                font: { size: 12 }
                                            }
                                        }
                                    }
                                }
                            });
                        }
                    }
                    
                    // Gráfico de Ensino
                    const ensinoCtx = document.getElementById('ensinoChart');
                    if (ensinoCtx) {
                        const rect = ensinoCtx.getBoundingClientRect();
                        if (rect.width > 0 && rect.height > 0) {
                            this.graficos.ensino = new Chart(ensinoCtx, {
                                type: 'pie',
                                data: {
                                    labels: ['Com Ensino Médio', 'Sem Ensino Médio'],
                                    datasets: [{
                                        data: [
                                            this.registros.filter(r => r.ensino_medio).length,
                                            this.registros.filter(r => !r.ensino_medio).length
                                        ],
                                        backgroundColor: ['#4cc9f0', '#7209b7'],
                                        borderWidth: 2,
                                        borderColor: '#fff'
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: { 
                                            position: 'bottom',
                                            labels: {
                                                padding: 20,
                                                usePointStyle: true,
                                                font: { size: 12 }
                                            }
                                        }
                                    }
                                }
                            });
                        }
                    }
                    
                    // Gráfico de Idade
                    const idadeCtx = document.getElementById('idadeChart');
                    if (idadeCtx) {
                        const rect = idadeCtx.getBoundingClientRect();
                        if (rect.width > 0 && rect.height > 0) {
                            const idades = this.registros.map(r => r.idade);
                            const nomes = this.registros.map(r => r.nome.split(' ')[0]);
                            
                            this.graficos.idade = new Chart(idadeCtx, {
                                type: 'bar',
                                data: {
                                    labels: nomes,
                                    datasets: [{
                                        label: 'Idade',
                                        data: idades,
                                        backgroundColor: '#6366f1',
                                        borderColor: '#4f46e5',
                                        borderWidth: 1,
                                        borderRadius: 4
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: { display: false }
                                    },
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            ticks: { font: { size: 11 } }
                                        },
                                        x: {
                                            ticks: { font: { size: 11 } }
                                        }
                                    }
                                }
                            });
                        }
                    }
                }, 500); 
            });
        }
    },
    watch: {
        abaAtiva: function(newVal) {
            if (newVal === 'graficos') {
                setTimeout(() => {
                    this.inicializarGraficos();
                }, 100);
            } else if (newVal === 'dashboard') {
                this.carregarEstatisticas();
            }
        },
        registros: function() {
            if (this.abaAtiva === 'graficos') {
                this.inicializarGraficos();
            }
        }
    },
    
    beforeDestroy() {
        this.destruirGraficos();
    }
});
</script>

</body>
</html>