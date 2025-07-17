## 🔧 開発環境セットアップ手順

1. Laravel コンテナに Lighthouse をインストール（初回のみ）

```bash
docker compose exec backend composer require nuwave/lighthouse

docker compose exec backend php artisan vendor:publish --tag=lighthouse-schema
docker compose exec backend php artisan vendor:publish --tag=lighthouse-config