# CPV Suggest - AI-Powered CPV Code Recommendation Service

A Laravel microservice that uses Anthropic Claude 3.5 to suggest relevant CPV (Common Procurement Vocabulary) codes based on company descriptions or website URLs.

## Quick Start

```bash
# 1. Copy environment file
cp .env.example .env

# 2. Add your Anthropic API key to .env
# ANTHROPIC_API_KEY=your-key-here

# 3. Start services
docker-compose up -d

# 4. Install dependencies and setup
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate --seed

# 5. Test the service
curl http://localhost:8080/api/healthz
```

## API Usage

### Suggest CPV Codes

```bash
POST /api/v1/cpv/suggest
Authorization: Bearer YOUR_TOKEN
Content-Type: application/json

{
  "description": "Wir bieten IT-Beratung und Cloud-Migration an",
  "language": "de",
  "top_k": 12
}
```

**Response:**
```json
{
  "query_id": "a1b2c3d4",
  "language_detected": "de",
  "codes": [
    {
      "cpv": "72222300",
      "title": "Informationstechnische Beratungsdienste",
      "path": ["72000000 - IT-Dienste", "72222300 - IT-Beratung"],
      "confidence": 0.87,
      "source": "llm+validated",
      "rationale": "IT consulting and cloud services"
    }
  ],
  "rationale": "Based on IT consulting and cloud migration keywords",
  "warnings": []
}
```

## Features

- > AI-powered suggestions using Claude 3.5 Sonnet
-  Validation against real CPV code database
- < Automatic website content extraction
- =¾ Redis caching for performance
- = API authentication via Laravel Sanctum
- <å Health check endpoints
- =Ê Hierarchical CPV code structure

## Documentation

See [CLAUDE.md](CLAUDE.md) for detailed architecture and development guide.

## License

MIT
