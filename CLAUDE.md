# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**cpv-suggest** is a Laravel-based microservice that uses AI (Anthropic Claude 3.5) to suggest relevant CPV (Common Procurement Vocabulary) codes based on company descriptions or website URLs. The service validates AI suggestions against a real CPV code database to prevent hallucinations.

### Key Features
- AI-powered CPV code suggestions using Claude 3.5 Sonnet
- Validation against internal CPV code database with hierarchical structure
- Synonym matching for better accuracy
- URL content extraction for automated analysis
- Redis caching for improved performance
- Rate limiting and authentication via Laravel Sanctum
- RESTful JSON API with structured responses

## Development Commands

### Initial Setup
```bash
# Copy environment file and configure
cp .env.example .env

# Edit .env and add your Anthropic API key:
# ANTHROPIC_API_KEY=your-key-here

# Start Docker containers
docker-compose up -d

# Install dependencies (inside app container)
docker-compose exec app composer install

# Generate application key
docker-compose exec app php artisan key:generate

# Run migrations and seed database
docker-compose exec app php artisan migrate --seed
```

### Running the Service
```bash
# Start all services
docker-compose up -d

# View logs
docker-compose logs -f app

# Stop services
docker-compose down
```

### Development Commands
```bash
# Access app container shell
docker-compose exec app bash

# Run migrations
docker-compose exec app php artisan migrate

# Seed database with CPV codes
docker-compose exec app php artisan db:seed

# Clear cache
docker-compose exec app php artisan cache:clear

# View routes
docker-compose exec app php artisan route:list

# Run tinker (Laravel REPL)
docker-compose exec app php artisan tinker
```

### API Endpoints

**Base URL**: `http://localhost:8080`

**Health Checks** (no auth):
- `GET /api/healthz` - Basic health check
- `GET /api/readyz` - Detailed readiness check (DB, Redis, CPV codes)

**CPV Suggestion** (requires auth):
- `POST /api/v1/cpv/suggest` - Suggest CPV codes

Example request:
```bash
curl -X POST http://localhost:8080/api/v1/cpv/suggest \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "description": "Wir bieten IT-Beratung und Cloud-Migration an",
    "language": "de",
    "top_k": 12
  }'
```

## Architecture

### Core Components

**CpvService** (`app/Services/CpvService.php`)
- Main business logic for CPV code suggestions
- Orchestrates URL fetching, LLM calls, and validation
- Handles caching (24-hour TTL)
- Key methods:
  - `suggest(array $input)` - Main entry point
  - `callAnthropic(string $prompt)` - LLM integration
  - `validateAgainstDb(array $candidates)` - Validates against CPV database

**Models**
- `CpvCode` - CPV code database model with hierarchical relationships (parent/children)
  - `getPath()` - Returns full hierarchical path
  - `search(string $query)` - Search by title or code
- `CpvSynonym` - Synonym mappings for better matching
  - `match(string $term)` - Find CPV code by synonym

**Controllers**
- `CpvSuggestController` - Handles `/api/v1/cpv/suggest` endpoint
- `HealthController` - Health and readiness checks

### Data Flow
1. Client sends description/URL to `/api/v1/cpv/suggest`
2. `CpvService` normalizes input (fetch URL if provided)
3. Build LLM prompt and call Anthropic Claude API
4. Parse JSON response with CPV code candidates
5. Validate each candidate against `cpv_codes` table
6. Try synonym matching for failed validations
7. Rank by confidence, deduplicate, return top K results
8. Cache result for 24 hours

### Database Schema

**cpv_codes**
- `code` (PK, string) - 8-digit CPV code
- `title` - Description
- `level` - Hierarchy level (1-5)
- `parent_code` - FK to parent code

**cpv_synonyms**
- `term` - Synonym text
- `code` - FK to cpv_codes
- `weight` - Matching confidence weight

### Configuration

**Anthropic API** (`config/services.php`):
- `services.anthropic.key` - API key (from .env)
- `services.anthropic.model` - Model name (default: claude-3-5-sonnet-20241022)
- `services.anthropic.url` - API endpoint

**Caching**: Redis-based, 24-hour TTL for suggestions

**Rate Limiting**: 60 requests per minute per user (configurable in routes/api.php)

### Adding CPV Codes

To add comprehensive CPV codes:
1. Place CSV file at `database/data/cpv.csv` with format: `code,title,level,parent_code`
2. Run `php artisan db:seed --class=CpvCodeSeeder`

The seeder will automatically load from CSV if present, otherwise uses sample data.

### Testing

Access the service:
```bash
# Check health
curl http://localhost:8080/api/healthz

# Check readiness (verifies DB, Redis, CPV codes loaded)
curl http://localhost:8080/api/readyz
```

### Common Issues

**"Anthropic API key not configured"**: Add `ANTHROPIC_API_KEY` to .env

**"CPV codes empty"**: Run `php artisan db:seed`

**Permission errors in Docker**: Check volume permissions in docker-compose.yml

**Cache not working**: Verify Redis connection in .env (`REDIS_HOST=redis`)
