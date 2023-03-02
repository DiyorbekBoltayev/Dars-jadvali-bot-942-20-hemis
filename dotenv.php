<?php
require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
if (getenv('APP_ENV') === 'production') {
    // Load environment variables from Heroku Config Vars
} else {
    // Load environment variables from .env file using Dotenv
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}