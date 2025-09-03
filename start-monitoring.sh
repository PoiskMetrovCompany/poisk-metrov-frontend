#!/bin/bash

echo "🚀 Запуск мониторинга..."

# Запуск сервисов мониторинга
docker-compose up -d prometheus grafana node-exporter

# Проверка статуса
echo "📊 Проверка статуса..."
docker ps --filter "name=prometheus|grafana|node" --format "table {{.Names}}\t{{.Status}}\t{{.Ports}}"

echo ""
echo "✅ Мониторинг запущен!"
echo "🌐 Grafana: http://localhost:3000 (admin/admin)"
echo "📈 Prometheus: http://localhost:9090"
echo ""
echo "💡 В Grafana добавьте datasource Prometheus: http://prometheus:9090"
