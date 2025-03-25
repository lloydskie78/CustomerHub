# CustomerHub

A modern full-stack customer management system built with Laravel, React, and Elasticsearch. Features real-time search, RESTful API, and containerized deployment.

## 🚀 Features

- Customer management (CRUD operations)
- Real-time search with Elasticsearch integration
- Modern, responsive UI with Material-UI
- RESTful API with Laravel
- Containerized development environment
- Load balanced with Nginx

## 💻 Tech Stack

- **Backend**: Laravel 8 (PHP 8.2)
- **Frontend**: React 18 with TypeScript
- **Database**: MySQL 8.0
- **Search Engine**: Elasticsearch 7.17.9
- **Load Balancer**: Nginx
- **Containerization**: Docker & Docker Compose

## 📋 Prerequisites

- Docker Desktop (latest version)
- Git
- Make sure ports 3000, 8000, 3306, and 9200 are available on your system

## 🛠️ Installation

1. Clone the repository:
```bash
git clone https://github.com/lloydskie78/CustomerHub.git
cd CustomerHub
```

2. Copy environment files:
```bash
cp api/.env.example api/.env
cp frontend/.env.example frontend/.env
```

3. Build and start the containers:
```bash
docker-compose up -d --build
```

4. The setup process will:
- Install all dependencies (both Laravel and React)
- Run database migrations
- Create Elasticsearch indices
- Start all required services

## 🌐 Accessing the Application

Once all containers are running successfully:

- **Frontend**: http://localhost:3000
- **Backend API**: http://localhost:8000/api
- **Elasticsearch**: http://localhost:9200

## 📦 Project Structure

```
.
├── api/                 # Laravel backend
│   ├── app/            # Application code
│   ├── database/       # Migrations and seeders
│   └── tests/          # Backend tests
├── frontend/           # React frontend
│   ├── src/            # Source code
│   │   ├── components/ # React components
│   │   └── services/   # API services
│   └── public/         # Static files
└── docker/            # Docker configuration files
```

## 🔄 API Endpoints

### Customers
- `GET /api/customers` - List all customers
- `GET /api/customers?search=query` - Search customers
- `GET /api/customers/{id}` - Get specific customer
- `POST /api/customers` - Create customer
- `PUT /api/customers/{id}` - Update customer
- `DELETE /api/customers/{id}` - Delete customer

## 🛠️ Development Commands

```bash
# View container logs
docker-compose logs -f [service-name]

# Access Laravel container
docker-compose exec api bash

# Run Laravel commands
docker-compose exec api php artisan [command]

# Run database migrations
docker-compose exec api php artisan migrate

# Access MySQL
docker-compose exec mysql mysql -u customer_user -p customer_db

# Check Elasticsearch indices
curl http://localhost:9200/_cat/indices
```

## 🧪 Running Tests

```bash
# Backend tests
docker-compose exec api php artisan test

# Frontend tests
docker-compose exec frontend npm test
```

## 🔍 Troubleshooting

If you encounter issues:

1. Check container status:
```bash
docker-compose ps
```

2. Verify services are healthy:
```bash
# MySQL
docker-compose exec mysql mysqladmin ping -h localhost

# Elasticsearch
curl http://localhost:9200/_cluster/health
```

3. If services are not starting:
```bash
# Remove all containers and volumes
docker-compose down -v

# Rebuild and start
docker-compose up -d --build
```

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'feat: Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License - see the LICENSE file for details. 