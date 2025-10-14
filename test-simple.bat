@echo off
echo Testing CPV Suggest API
echo.
echo Writing output to test-output.txt...
echo.

echo === CPV Suggestion Test === > test-output.txt
curl -X POST http://localhost:8080/api/v1/cpv/suggest -H "Content-Type: application/json" -d "{\"description\":\"IT-Beratung\",\"language\":\"de\",\"top_k\":5}" >> test-output.txt 2>&1

echo Done! Check test-output.txt for results
notepad test-output.txt
