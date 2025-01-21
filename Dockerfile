# Utilisation de l'image officielle PHP
FROM php:7.4-apache

# Copie de votre code dans le conteneur
COPY . /var/www/html/

# Exposer le port 80 pour l'accès HTTP
EXPOSE 80
