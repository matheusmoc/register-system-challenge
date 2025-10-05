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