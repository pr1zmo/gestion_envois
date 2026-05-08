# Gestion Envois

A containerized PHP web application for managing shipments (Poste Maroc). 

## 🚀 Architecture

The project is fully containerized using Docker and consists of the following services:
- **nginx**: The web server, exposed on port `8443`.
- **backend**: PHP 8.2-FPM application serving the core logic.
- **database**: MySQL database storing the application data (`poste_maroc`).
- **adminer**: Database management tool, exposed on port `8081`.

## 📋 Prerequisites

- **Docker** & **Docker Compose** installed on your system.
- **Make** (optional, for utilizing the Makefile shortcuts).

## 🛠️ Quick Start

1. Start the stack in detached mode:
   ```bash
   make up
   ```
   *(Or run `docker compose up -d` if you do not have Make installed).*

2. Access the application:
   - **Main Web App:** [http://localhost:8443](http://localhost:8443)
   - **Adminer (Database Manager):** [http://localhost:8081](http://localhost:8081)

## 🗄️ Database Credentials

When logging into Adminer or configuring local clients, use the following credentials:
- **System:** `MySQL`
- **Server:** `database`
- **Username:** `root`
- **Password:** `root`
- **Database:** `poste_maroc`

## 📜 Makefile Commands

For convenience, a `Makefile` is included to manage the Docker lifecycle:

- `make up` : Start all containers in the background.
- `make down` : Stop and remove containers and networks.
- `make stop` : Pause containers without removing them.
- `make start` : Resume paused containers.
- `make build` : Rebuild the Docker images.
- `make logs` : Tail the logs of all running services.
- `make clean` : Stop and completely remove containers, networks, and all volumes (Warning: resets the database!).
- `make restart` : Restart all containers.
