php_flag display_errors off
php_value error_reporting 32767

RewriteEngine On

# Angiv PHP-filtyper
AddType application/x-httpd-php .php

# Start omskrivning for alle anmodninger, der ikke er en fil eller mappe
RewriteBase /Exam-1sem-bio/
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.+)$ index.php?page=$1 [QSA,L]

# Rewriter URL'er der har en slug som 'movie/film-titel'
RewriteRule ^movie/([a-zA-Z0-9-_]+)$ index.php?page=movieDetails&slug=$1 [L,QSA]