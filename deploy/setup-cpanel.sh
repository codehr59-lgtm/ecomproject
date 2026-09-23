#!/bin/bash
# ============================================================
# cPanel Deploy Script for sourcentra.com.bd
# Run this in cPanel Terminal after uploading files
# ============================================================

echo "Setting up sourcentra.com.bd..."

# ── Step 1: Permissions ──
echo "Setting permissions..."
cd ~/ecom-shuvo
chmod -R 775 storage
chmod -R 775 bootstrap/cache
chmod 664 database/database.sqlite 2>/dev/null

# ── Step 2: Storage link ──
echo "Creating storage symlink..."
rm -f ~/public_html/storage
ln -s /home/sourcentra/ecom-shuvo/storage/app/public /home/sourcentra/public_html/storage
echo "Storage linked."

# ── Step 3: Cache ──
echo "Caching config/routes/views..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# ── Step 4: Migrate ──
echo "Running migrations..."
php artisan migrate --force

echo ""
echo "Done! Site should be live at https://sourcentra.com.bd"
echo ""
echo "Remember to:"
echo "  1. Set PHP version to 8.2+ in MultiPHP Manager"
echo "  2. Fill in payment/courier credentials in .env"
echo ""
