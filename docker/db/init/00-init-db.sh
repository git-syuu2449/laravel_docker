#!/bin/bash
set -e

# .env ファイルを読み込む
export $(cat ../.env | xargs)

# デフォルト環境変数（docker-compose.ymlで設定しておくことを推奨）
DATABASE=${MYSQL_DATABASE:-laravel_db}
TEST_DATABASE=${MYSQL_TEST_DATABASE:-test_db}

echo "Creating databases: $DATABASE and $TEST_DATABASE"

mysql -u root -p"$MYSQL_ROOT_PASSWORD" <<-EOSQL
  CREATE DATABASE IF NOT EXISTS \`$DATABASE\`;
  CREATE DATABASE IF NOT EXISTS \`$TEST_DATABASE\`;
EOSQL
