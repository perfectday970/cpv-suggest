@echo off
echo Testing CPV Suggest API
echo.

echo 1. Health Check:
curl http://localhost:8080/api/healthz
echo.
echo.

echo 2. Readiness Check:
curl http://localhost:8080/api/readyz
echo.
echo.

echo 3. CPV Suggestion (German IT Consulting):
curl -X POST http://localhost:8080/api/v1/cpv/suggest ^
  -H "Content-Type: application/json" ^
  -d "{\"description\":\"Wir bieten IT-Beratung und Cloud-Migration an\",\"language\":\"de\",\"top_k\":5}"
echo.
echo.

echo 4. CPV Suggestion (English):
curl -X POST http://localhost:8080/api/v1/cpv/suggest ^
  -H "Content-Type: application/json" ^
  -d "{\"description\":\"We provide software development and system architecture consulting\",\"language\":\"en\",\"top_k\":5}"
echo.

pause
