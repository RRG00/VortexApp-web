# VortexApp

Uma aplicação web abrangente construída sobre o Template Avançado de Projeto Yii2, concebida para desenvolvimento multi-nível complexo com aplicações frontend, backend e consola.

## 🛠️ Stack Tecnológica

- **Framework**: Yii2 Advanced Template
- **Linguagem**: PHP 8.x
- **Base de Dados**: MySQL/MariaDB
- **Message Broker**: Mosquitto (MQTT)
- **Containerização**: Docker & Docker Compose
- **Testes**: Codeception
- **Frontend**: HTML, JavaScript, CSS
- **Controlo de Versão**: Git

## 📁 Estrutura de Diretórios

```
VortexApp-web/
│
├── backend/                # Aplicação backend
│   ├── assets/            # Assets backend (JS, CSS)
│   ├── config/            # Configurações backend
│   ├── controllers/       # Controladores backend
│   ├── models/            # Modelos específicos backend
│   ├── runtime/           # Ficheiros runtime
│   ├── tests/             # Testes backend
│   ├── views/             # Vistas backend
│   └── web/               # Ponto de entrada web
│
├── frontend/              # Aplicação frontend
│   ├── assets/            # Assets frontend (JS, CSS)
│   ├── config/            # Configurações frontend
│   ├── controllers/       # Controladores frontend
│   ├── models/            # Modelos específicos frontend
│   ├── runtime/           # Ficheiros runtime
│   ├── tests/             # Testes frontend
│   ├── views/             # Vistas frontend
│   ├── web/               # Ponto de entrada web
│   └── widgets/           # Widgets frontend
│
├── console/               # Aplicação consola
│   ├── config/            # Configurações consola
│   ├── controllers/       # Comandos consola
│   ├── migrations/        # Migrações de base de dados
│   ├── models/            # Modelos específicos consola
│   └── runtime/           # Ficheiros runtime
│
├── common/                # Componentes partilhados
│   ├── config/            # Configurações partilhadas
│   ├── mail/              # Templates de email
│   ├── models/            # Modelos partilhados
│   └── tests/             # Testes comuns
│
├── environments/          # Configurações de ambiente
├── mosquitto/             # Configuração do broker MQTT
├── vagrant/               # Configuração VM Vagrant
├── docker-compose.yml     # Definição de serviços Docker
├── Vagrantfile            # Configuração Vagrant
└── composer.json          # Dependências PHP
```

## 📋 Pré-requisitos

- **PHP** >= 8.3.14 (recomendado 8.0+)
- **Composer** - Gestor de dependências para PHP
- **MySQL/MariaDB** - Servidor de base de dados
- **Docker** (opcional) - Para implementação containerizada
- **Git** - Controlo de versão

## 🚀 Instalação

### Instalação Standard

1. **Clonar o repositório**
   ```bash
   git clone https://github.com/RRG00/VortexApp-web.git
   cd VortexApp-web
   ```

2. **Instalar dependências**
   ```bash
   composer install
   ```

3. **Inicializar a aplicação**
   ```bash
   php init
   ```
   
4. **Inicializar a aplicação**
   ```bash
   php yii rbac/init
   ```
   
   Selecione o seu ambiente (0 para Desenvolvimento, 1 para Produção)

5. **Configurar base de dados**
   
   Edite `common/config/main-local.php` e defina a sua ligação à base de dados:
   ```php
   'db' => [
       'class' => 'yii\db\Connection',
       'dsn' => 'mysql:host=localhost;dbname=sua_base_dados',
       'username' => 'seu_utilizador',
       'password' => 'sua_password',
       'charset' => 'utf8',
   ],
   ```
   
6. **Configurar servidor web**
   
   Aponte a raiz do documento do seu servidor web para:
   - Frontend: `/caminho/para/VortexApp-web/frontend/web`
   - Backend: `/caminho/para/VortexApp-web/backend/web`


### Configuração MQTT

1. **Instalar MQTT**
   ```bash
   mosquitto-2.0.15-install-windows-x64.exe /S
   ```

## 🎮 Utilização

### Aplicações Web

- **Frontend**: Aceder em `http://localhost` (ou o seu domínio configurado)
- **Backend**: Aceder em `http://localhost/admin` (ou o seu domínio configurado)

### Comandos de Consola

Execute comandos de consola usando:
```bash
php yii <comando>
```

Exemplos de comandos:
```bash
# Listar todos os comandos disponíveis
php yii help

# Executar migrações de base de dados
php yii migrate

# Criar nova migração
php yii migrate/create <nome>
```

## 🧪 Testes

A aplicação utiliza Codeception para testes.

### Executar suites de teste específicas
```bash
# Teste Funcional
php vendor/bin/codecept run functional NomedoTeste -c frontend | backend | common

# Teste Unitário
php vendor/bin/codecept run unit NomedoTeste -c frontend | backend | common
```

### Contribuidores

- [Guilherme Sá](https://github.com/GS1-Hub)
- [RRG00](https://github.com/RRG00)
- [Guilherme Reis](https://github.com/PomaxS22)

## 📄 Licença

Este projeto está licenciado sob a Licença BSD-3-Clause - consulte o ficheiro [LICENSE.md](LICENSE.md) para detalhes.

## 📞 Suporte

Para questões, perguntas ou contribuições, por favor:
- Abra uma issue no [GitHub Issues](https://github.com/RRG00/VortexApp-web/issues)
- Contacte a equipa de desenvolvimento

## 🔗 Links Úteis

- [Documentação Yii2](https://www.yiiframework.com/doc/guide/2.0/en)
- [Yii2 Advanced Template](https://github.com/yiisoft/yii2-app-advanced)
- [Documentação Composer](https://getcomposer.org/doc/)
- [Documentação Docker](https://docs.docker.com/)
- [Documentação Codeception](https://codeception.com/docs)

---

**Construído com ❤️ usando a Framework Yii2**
