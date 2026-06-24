# RecipeBook API

A recipe management system REST API built with **PHP / Symfony**, following **Domain-Driven Design (DDD)** principles and a clean layered architecture.

## Overview

RecipeBook API is the backend for a recipe management application. It allows users to manage recipes, ingredient types, and related culinary data through a RESTful HTTP interface.

The project is designed from the ground up around DDD concepts: bounded contexts, aggregates, domain events, repository pattern, and CQRS — ensuring business logic stays in the domain and infrastructure concerns are fully decoupled.

## Architecture

The codebase is organised into **bounded context modules** under `src/`. Each module follows a strict four-layer structure:

```
src/
├── Shared/                     # Shared Kernel — cross-cutting abstractions
│   ├── Domain/                 # Base classes, value objects, domain events
│   ├── Application/            # Query bus, shared query contracts, service ports
│   ├── Infrastructure/         # Symfony/Doctrine implementations of shared ports
│   └── Presentation/           # Health check HTTP controller
│
└── IngredientType/             # Bounded context: ingredient type catalogue
    ├── Domain/
    │   ├── Model/              # Aggregate root (IngredientType)
    │   └── Repository/         # Repository interface (domain port)
    ├── Application/
    │   ├── Query/              # CQRS queries, handlers, DTOs, responses
    │   ├── Command/            # CQRS commands, handlers, DTOs, responses
    │   └── Service/            # Application service ports (e.g. ItemsPager)
    ├── Infrastructure/
    │   ├── Repository/         # Doctrine implementation of the repository
    │   ├── DoctrineMapping/    # XML ORM mapping files
    │   └── DoctrineIngredientTypesListPager.php
    └── Presentation/
        └── Http/               # Controllers and JSON response factories
```

### Layer responsibilities

| Layer | Responsibility |
|---|---|
| **Domain** | Aggregates, value objects, domain events, repository interfaces. No framework dependencies. |
| **Application** | Use cases (query/command handlers), DTOs, application service ports. Depends only on Domain. |
| **Infrastructure** | Doctrine repositories, ORM mappings, framework adapters. Implements domain and application ports. |
| **Presentation** | HTTP controllers and response factories. Depends on Application layer only. |

### Key design decisions

- **CQRS via Symfony Messenger** — queries are dispatched through a `QueryBus` and handled by dedicated `QueryHandler` classes.
- **Repository pattern** — domain repository interfaces live in the Domain layer. Doctrine implementations live in Infrastructure.
- **DTOs for read models** — query handlers map domain entities to plain DTOs before returning. Domain objects never leave the Application layer.
- **Domain events** — aggregates extend `AggregateRoot`, which provides `addDomainEvent` / `pullDomainEvents` for event sourcing readiness.
- **Value objects** — entity identities are wrapped in `AggregateRootId` (backed by ULID).
- **XML Doctrine mappings** — ORM configuration is kept in `Infrastructure/DoctrineMapping/`, cleanly separated from the domain model.

## Tech Stack

| Concern          | Technology                           |
|------------------|--------------------------------------|
| Language         | PHP 8.4+                             |
| Framework        | Symfony 8.1                          |
| ORM              | Doctrine ORM (XML mappings)          |
| Database         | PostgreSQL 16                        |
| Message Bus      | Symfony Messenger (sync transport)   |
| API              | Custom REST endpoints + API Platform |
| Containerisation | Docker / Docker Compose              |

## Requirements

- Docker and Docker Compose
- PHP 8.4+ with Composer (for local development without Docker)

## Getting started

### 1. Clone and install dependencies

```bash
composer install
```

### 2. Configure environment

Create `.env.local` and set your database URL:

```bash
touch .env.local
```

Add the lines:
```
APP_SECRET=
DATABASE_URL="postgresql://app:!ChangeMe!@127.0.0.1:5432/app?serverVersion=16&charset=utf8"
```

The default `DATABASE_URL` points to the Docker Compose database. Adjust to your needs.

### 3. Start the containers

```bash
docker compose up -d
```

In particular, this starts a PostgreSQL 16 container on port `5432`.

### 4. Run database migrations

```bash
php bin/console doctrine:migrations:migrate
```

### 5. Start the development server

```bash
symfony server:start
```

## Running tests

Unit and functional tests are located in the `tests/**` subdirectories. We are using [PHPUnit](https://phpunit.de/)
for testing the application.

```bash
php bin/phpunit
```

For convenience, there are a few .http files in the `tests/Functional/**` subdirectories to test the API endpoints.
Those tests are meant to be run with [HTTPie](https://httpie.io/) and are a helpful way to test the API endpoints.

## Project conventions

- Each bounded context is a self-contained module under `src/`.
- The Domain layer has **zero** framework or infrastructure dependencies.
- All domain mutations go through intention-revealing methods (e.g. `rename()`), never via public setters.
- Domain aggregates are `final` to prevent inheritance from bypassing invariants.
- DTOs carry primitive types only — no domain objects cross the Application boundary.
