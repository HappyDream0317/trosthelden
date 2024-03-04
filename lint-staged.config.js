module.exports = {
  'resources/**/*.{css,js,vue,scss}': ['prettier --write'],
  '**/*.php': ['docker-compose exec -T app php ./vendor/bin/php-cs-fixer fix --config .php-cs-fixer.php'],
};