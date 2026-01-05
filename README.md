# VortexApp

A comprehensive web application built on the Yii2 Advanced Project Template, designed for complex multi-tier development with frontend, backend, and console applications.

## 📋 Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Directory Structure](#directory-structure)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Docker Setup](#docker-setup)
- [Testing](#testing)
- [Contributing](#contributing)
- [License](#license)

## 🎯 Overview

VortexApp is a sophisticated web application leveraging the Yii2 framework's advanced template architecture. It provides a robust foundation for building scalable applications with clear separation between frontend, backend, and console components.

## ✨ Features

- **Multi-tier Architecture**: Separate frontend, backend, and console applications
- **MQTT Integration**: Real-time messaging with Mosquitto broker
- **Docker Support**: Containerized deployment with Docker Compose
- **Vagrant Integration**: VM-based development environment
- **Environment Management**: Multiple environment configurations (dev, prod, test)
- **Database Migrations**: Version-controlled database schema management
- **Testing Framework**: Integrated Codeception for comprehensive testing
- **Shared Components**: Common models and configurations across tiers

## 🛠️ Tech Stack

- **Framework**: Yii2 Advanced Template
- **Language**: PHP 8.x
- **Database**: MySQL/MariaDB
- **Message Broker**: Mosquitto (MQTT)
- **Containerization**: Docker & Docker Compose
- **Virtual Machine**: Vagrant
- **Testing**: Codeception
- **Frontend**: JavaScript, CSS
- **Version Control**: Git

## 📁 Directory Structure

```
VortexApp-web/
│
├── backend/                # Backend application
│   ├── assets/            # Backend assets (JS, CSS)
│   ├── config/            # Backend configurations
│   ├── controllers/       # Backend controllers
│   ├── models/            # Backend-specific models
│   ├── runtime/           # Runtime files
│   ├── tests/             # Backend tests
│   ├── views/             # Backend views
│   └── web/               # Web entry point
│
├── frontend/              # Frontend application
│   ├── assets/            # Frontend assets (JS, CSS)
│   ├── config/            # Frontend configurations
│   ├── controllers/       # Frontend controllers
│   ├── models/            # Frontend-specific models
│   ├── runtime/           # Runtime files
│   ├── tests/             # Frontend tests
│   ├── views/             # Frontend views
│   ├── web/               # Web entry point
│   └── widgets/           # Frontend widgets
│
├── console/               # Console application
│   ├── config/            # Console configurations
│   ├── controllers/       # Console commands
│   ├── migrations/        # Database migrations
│   ├── models/            # Console-specific models
│   └── runtime/           # Runtime files
│
├── common/                # Shared components
│   ├── config/            # Shared configurations
│   ├── mail/              # Email templates
│   ├── models/            # Shared models
│   └── tests/             # Common tests
│
├── environments/          # Environment configurations
├── mosquitto/             # MQTT broker configuration
├── vagrant/               # Vagrant VM configuration
├── docker-compose.yml     # Docker services definition
├── Vagrantfile            # Vagrant configuration
└── composer.json          # PHP dependencies
```

## 📋 Prerequisites

- **PHP** >= 7.4 (recommended 8.0+)
- **Composer** - Dependency manager for PHP
- **MySQL/MariaDB** - Database server
- **Docker** (optional) - For containerized deployment
- **Vagrant** (optional) - For VM-based development
- **Git** - Version control

## 🚀 Installation

### Standard Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/RRG00/VortexApp-web.git
   cd VortexApp-web
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Initialize the application**
   
   For Linux/Mac:
   ```bash
   php init
   ```
   
   For Windows:
   ```bash
   init.bat
   ```
   
   Select your environment (0 for Development, 1 for Production)

4. **Configure database**
   
   Edit `common/config/main-local.php` and set your database connection:
   ```php
   'db' => [
       'class' => 'yii\db\Connection',
       'dsn' => 'mysql:host=localhost;dbname=your_database',
       'username' => 'your_username',
       'password' => 'your_password',
       'charset' => 'utf8',
   ],
   ```

5. **Run migrations**
   ```bash
   php yii migrate
   ```

6. **Set up web server**
   
   Point your web server's document root to:
   - Frontend: `/path/to/VortexApp-web/frontend/web`
   - Backend: `/path/to/VortexApp-web/backend/web`

### Docker Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/RRG00/VortexApp-web.git
   cd VortexApp-web
   ```

2. **Start Docker containers**
   ```bash
   docker-compose up -d
   ```

3. **Initialize inside container**
   ```bash
   docker-compose exec php php init
   ```

4. **Run migrations**
   ```bash
   docker-compose exec php php yii migrate
   ```

### Vagrant Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/RRG00/VortexApp-web.git
   cd VortexApp-web
   ```

2. **Start Vagrant VM**
   ```bash
   vagrant up
   ```

3. **SSH into the VM**
   ```bash
   vagrant ssh
   ```

4. **Follow standard installation steps inside VM**

## ⚙️ Configuration

### Environment Configuration

The application supports multiple environments:
- **Development** (`environments/dev`)
- **Production** (`environments/prod`)

Switch environments by running:
```bash
php init
```

### MQTT Configuration

Edit `mosquitto/mosquitto.conf` to configure the MQTT broker settings.

### Application Parameters

Edit the following files to configure application-specific parameters:
- `common/config/params.php` - Shared parameters
- `frontend/config/params.php` - Frontend parameters
- `backend/config/params.php` - Backend parameters

## 🎮 Usage

### Web Applications

- **Frontend**: Access at `http://localhost` (or your configured domain)
- **Backend**: Access at `http://localhost/admin` (or your configured domain)

### Console Commands

Run console commands using:
```bash
php yii <command>
```

Example commands:
```bash
# List all available commands
php yii help

# Run database migrations
php yii migrate

# Create new migration
php yii migrate/create <name>
```

### Running with Docker

```bash
# Start all services
docker-compose up -d

# View logs
docker-compose logs -f

# Stop services
docker-compose down

# Execute commands in container
docker-compose exec php php yii <command>
```

## 🧪 Testing

The application uses Codeception for testing.

### Run all tests
```bash
composer run-script test
```

### Run specific test suites
```bash
# Backend tests
./vendor/bin/codecept run -c backend

# Frontend tests
./vendor/bin/codecept run -c frontend

# Common tests
./vendor/bin/codecept run -c common

# Console tests
./vendor/bin/codecept run -c console
```

## 👥 Contributing

Contributions are welcome! Please follow these guidelines:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

### Contributors

- [Guilherme Sá](https://github.com/GS1-Hub)
- [RRG00](https://github.com/RRG00)
- [Guilherme Reis](https://github.com/PomaxS22)

## 📄 License

This project is licensed under the BSD-3-Clause License - see the [LICENSE.md](LICENSE.md) file for details.

## 📞 Support

For issues, questions, or contributions, please:
- Open an issue on [GitHub Issues](https://github.com/RRG00/VortexApp-web/issues)
- Contact the development team

## 🔗 Useful Links

- [Yii2 Documentation](https://www.yiiframework.com/doc/guide/2.0/en)
- [Yii2 Advanced Template](https://github.com/yiisoft/yii2-app-advanced)
- [Composer Documentation](https://getcomposer.org/doc/)
- [Docker Documentation](https://docs.docker.com/)
- [Codeception Documentation](https://codeception.com/docs)

---

**Built with ❤️ using Yii2 Framework**