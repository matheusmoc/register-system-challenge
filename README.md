# Sistema de Cadastro - Desafio

##  Descrição do Sistema

Este é um sistema full-stack desenvolvido em Laravel 12 + Vue 2 para gerenciamento de cadastros de pessoas. O sistema permite criar, visualizar, editar e excluir registros com validações completas tanto no frontend quanto no backend.

<img width="1919" height="927" alt="image" src="https://github.com/user-attachments/assets/fd9b2570-b364-4c1a-9786-2f1f75abe675" />


##  Funcionalidades

- ✅ **CRUD completo** de cadastros
- ✅ **Validações** robustas no frontend e backend
- ✅ **Integração com ViaCEP** para preenchimento automático de endereço
- ✅ **Upload de arquivos** (PDF, JPG, PNG) até 10MB
- ✅ **Interface responsiva** com Bootstrap
- ✅ **Máscaras de entrada** para CEP e salário
- ✅ **Confirmações** para ações destrutivas
- ✅ **Ordenação** dos registros por ID decrescente

<img width="1506" height="474" alt="image" src="https://github.com/user-attachments/assets/6f81daa8-be4a-45ed-a10b-1c5b891fddc1" />
<img width="1560" height="530" alt="image" src="https://github.com/user-attachments/assets/8c4f10b0-7965-4af4-a745-c1a8b6cffc30" />
<img width="1599" height="849" alt="image" src="https://github.com/user-attachments/assets/22e50eea-87eb-43cb-b6a0-ca697d1eab44" />

## Tecnologias Utilizadas

- **Backend**: Laravel 12, PHP 8.2
- **Frontend**: Bootstrap 5, Vue 2, CSS3
- **Banco de Dados**: MySQL
- **Containerização**: Docker & Docker Compose

## 🐳 Instalação com Docker

### Pré-requisitos
- Docker
- Docker Compose

### Passo a Passo

```bash
git clone https://github.com/seu-usuario/desafio-avelar.git
cd desafio-avelar

cp .env.example .env

# Inicie os containers
docker-compose up -d

# Execute os comandos do Laravel dentro do container
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan storage:link

http://localhost:8000

# Para parar a aplicação utilizar
docker-compose down
```

## Estrutura do Banco de Dados

```sql
CREATE TABLE dados (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(150) NOT NULL,
  idade INT NOT NULL,
  cep VARCHAR(13) NOT NULL,
  cidade VARCHAR(100) NOT NULL,
  estado VARCHAR(2) NOT NULL,
  rua VARCHAR(150) NOT NULL,
  bairro VARCHAR(100) NOT NULL,
  ensino_medio TINYINT(1) NOT NULL,
  sexo VARCHAR(20) NOT NULL,
  salario DECIMAL(12,2) NOT NULL,
  anexo VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

## Validações

- Campos obrigatórios destacados
- Validação de idade (número positivo)
- Formato de CEP (99.999-999)
- Máscara de salário (formato brasileiro)
- Validação de arquivo (tipo e tamanho)


```php
$validated = $request->validate([
    'nome' => 'required|string|max:150',
    'idade' => 'required|integer|min:1',
    'cep' => 'required|string|max:13',
    'cidade' => 'required|string|max:100',
    'estado' => 'required|string|size:2',
    'rua' => 'required|string|max:150',
    'bairro' => 'required|string|max:100',
    'ensino_medio' => 'boolean',
    'sexo' => 'required|in:Masculino,Feminino,Outro',
    'salario' => 'required|numeric|min:0',
    'anexo' => 'required|file|mimes:pdf,jpg,png|max:10240'
]);
```
<img width="1906" height="886" alt="image" src="https://github.com/user-attachments/assets/dbea4487-fd6d-4e2b-9d5b-305a30d07c75" />


## 🌐 API Endpoints

| Método | Endpoint | Descrição | Controller Method |
|--------|-----------|------------|-------------------|
| GET | `/` | Página principal com formulário | `index()` |
| GET | `/cadastro/listar` | Listar todos os registros (JSON) | `listar()` |
| GET | `/cadastro/estatisticas` | Obter estatísticas dos dados | `estatisticas()` |
| POST | `/cadastro` | Criar novo registro | `store()` |
| GET | `/cadastro/{id}` | Buscar registro específico | `show($id)` |
| PUT | `/cadastro/{id}` | Atualizar registro existente | `update($id)` |
| DELETE | `/cadastro/{id}` | Excluir registro | `destroy($id)` |

### Detalhamento dos Endpoints

#### GET `/`
- **Descrição**: Retorna a view principal com formulário de cadastro
- **Resposta**: HTML da página

#### GET `/cadastro/listar`
- **Descrição**: Retorna todos os registros em formato JSON
- **Resposta**: 
```json
{
  "data": [
    {
      "id": 1,
      "nome": "João Silva",
      "idade": 30,
      "cep": "01.234-567",
      "cidade": "São Paulo",
      "estado": "SP",
      "rua": "Rua das Flores",
      "bairro": "Centro",
      "ensino_medio": 1,
      "sexo": "Masculino",
      "salario": "3000.00",
      "anexo": "arquivo_123.pdf",
      "created_at": "2024-01-15 10:30:00"
    }
  ]
}
