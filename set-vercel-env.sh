#!/bin/bash
set -e

echo "Adding environment variables to Vercel..."

add_env() {
  echo "$2" | vercel env add "$1" production --force 2>/dev/null || echo "$2" | vercel env add "$1" production
}

add_env APP_KEY "base64:521efZ9fWpPKBIj4vV4pHfg1T+ndi0ZSxXFI0HjmuaM="
add_env APP_NAME "Warehouse Management System"
add_env APP_ENV "production"
add_env APP_DEBUG "false"
add_env APP_URL "https://warehouse-management-system-ecru.vercel.app"
add_env SESSION_DRIVER "cookie"
add_env CACHE_DRIVER "array"
add_env LOG_CHANNEL "stderr"
add_env VIEW_COMPILED_PATH "/tmp"
add_env APP_CONFIG_CACHE "/tmp/config.php"
add_env APP_EVENTS_CACHE "/tmp/events.php"
add_env APP_PACKAGES_CACHE "/tmp/packages.php"
add_env APP_ROUTES_CACHE "/tmp/routes.php"
add_env APP_SERVICES_CACHE "/tmp/services.php"
add_env QUEUE_CONNECTION "sync"
add_env DB_CONNECTION "sqlite"
add_env DB_DATABASE "/tmp/database.sqlite"

echo "Done! All environment variables added."
echo "Now run: vercel --prod"
