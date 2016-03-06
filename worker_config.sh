#!/usr/bin/env bash
sudo apt-get update
sudo apt-get install -y php5
sudo apt-get install -y php5-curl
curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer
composer global require "laravel/lumen-installer"
