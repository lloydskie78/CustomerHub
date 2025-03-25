# Customer Management System

A full-stack CRUD application for managing customer records, built with Laravel, React, and Docker.

## Features

- Create, Read, Update, and Delete customers
- Search customers by name and email
- Real-time search using Elasticsearch
- Modern UI with Material-UI
- Containerized development environment

## Tech Stack

- Backend: Laravel 8 (PHP 8.2)
- Frontend: React with TypeScript
- Database: MySQL 8.0
- Search Engine: Elasticsearch 7.17.9
- Container Orchestration: Docker & Docker Compose
- Load Balancer: Nginx

## Prerequisites

Before you begin, ensure you have the following installed on your system:
- Docker Desktop (latest version)
- Git

## Project Structure

```
.
├── api/                # Laravel backend
├── frontend/          # React frontend
├── docker/            # Docker configuration files
└── docker-compose.yml # Docker compose configuration
```

## Installation & Setup

1. Clone the repository:
```bash
git clone <repository-url>
cd customer-management-system
```

2. Create backend environment file:
```bash
cp api/.env.example api/.env
```

3. Create frontend environment file:
```bash
cp frontend/.env.example frontend/.env
```

4. Start the Docker containers:
```bash
# Remove any existing containers and volumes (if needed)
docker-compose down -v

# Build and start the containers
docker-compose up -d --build
```

The setup process will:
- Install all dependencies
- Run database migrations
- Set up Elasticsearch indices
- Start all required services

## Accessing the Application

Once all containers are running, you can access:

- Frontend Application: http://localhost:3000
- Backend API: http://localhost:8000/api
- Elasticsearch: http://localhost:9200

## Available Services

The application runs the following services:

- `api`: Laravel backend service (PHP-FPM)
- `nginx`: Load balancer/web server
- `mysql`: Database service
- `elasticsearch`: Search service
- `frontend`: React application

## API Endpoints

### Customers

- `GET /api/customers` - List all customers
- `GET /api/customers?search=query` - Search customers
- `GET /api/customers/{id}` - Get a specific customer
- `POST /api/customers` - Create a new customer
- `PUT /api/customers/{id}` - Update a customer
- `DELETE /api/customers/{id}` - Delete a customer

## Development

### Useful Docker Commands

```bash
# View container logs
docker-compose logs -f [service-name]

# Restart a specific service
docker-compose restart [service-name]

# Run Laravel commands
docker-compose exec api php artisan [command]

# Access MySQL CLI
docker-compose exec mysql mysql -u customer_user -p customer_db

# View Elasticsearch indices
curl http://localhost:9200/_cat/indices
```

### Running Tests

```bash
# Run PHP tests
docker-compose exec api php artisan test

# Run React tests
docker-compose exec frontend npm test
```

### Troubleshooting

If you encounter any issues:

1. Check container status:
```bash
docker-compose ps
```

2. View container logs:
```bash
docker-compose logs -f api
```

3. Rebuild containers:
```bash
docker-compose down -v
docker-compose up -d --build
```

4. Check service health:
```bash
# MySQL
docker-compose exec mysql mysqladmin ping -h localhost

# Elasticsearch
curl http://localhost:9200/_cluster/health
```

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details. 