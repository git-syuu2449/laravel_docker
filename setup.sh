#!/bin/bash
# laravel/.env を docker/.env の変数で生成

set -a
source .env  # docker/.env を読み込み
set +a

envsubst < laravel-project/.env.template > laravel-project/.env
