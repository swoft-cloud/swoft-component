#!/usr/bin/env bash

CURRENT_DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
TRAVIS_BUILD_DIR="${TRAVIS_BUILD_DIR:-$(dirname $(dirname $CURRENT_DIR))}"

echo -e "Create MySQL database..."
mysql -u root -e "CREATE DATABASE IF NOT EXISTS test charset=utf8mb4 collate=utf8mb4_unicode_ci;"
cat "${TRAVIS_BUILD_DIR}/test/_ci/mysql.sql" | mysql -u root test
mysql -u root -e "CREATE DATABASE IF NOT EXISTS test2 charset=utf8mb4 collate=utf8mb4_unicode_ci;"
cat "${TRAVIS_BUILD_DIR}/test/_ci/mysql2.sql" | mysql -u root test

echo -e "Done\n"

wait
