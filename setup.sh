#!/bin/bash

# CPV Suggest Service - Setup Script

set -e

echo "🚀 Setting up CPV Suggest Service..."

# Check if .env exists
if [ ! -f .env ]; then
    echo "📄 Copying .env.example to .env..."
    cp .env.example .env
    echo "⚠️  Please edit .env and add your ANTHROPIC_API_KEY"
    echo ""
fi

echo "🐳 Starting Docker containers..."
docker-compose up -d

echo "⏳ Waiting for database to be ready..."
sleep 5

echo "📦 Installing Composer dependencies..."
docker-compose exec -T app composer install --no-interaction

echo "🔑 Generating application key..."
docker-compose exec -T app php artisan key:generate --force

echo "🗄️  Running migrations..."
docker-compose exec -T app php artisan migrate --force

echo "🌱 Seeding database with CPV codes..."
docker-compose exec -T app php artisan db:seed --force

echo ""
echo "✅ Setup complete!"
echo ""
echo "🌐 Service available at: http://localhost:8080"
echo ""
echo "📋 Next steps:"
echo "  1. Edit .env and add your ANTHROPIC_API_KEY"
echo "  2. Restart services: docker-compose restart"
echo "  3. Test health: curl http://localhost:8080/api/healthz"
echo "  4. Check readiness: curl http://localhost:8080/api/readyz"
echo ""
echo "📚 See CLAUDE.md for full documentation"
