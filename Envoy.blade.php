@setup
$environment = isset($env) ? $env : 'staging';

$versions_to_keep = 3;
$repository = 'git@gitlab.com:Cyncode/TB-Community.git';
$release = $branch . '+' . date('YmdHis');

$php_bin = '/opt/plesk/php/7.4/bin/php';
$composer_bin = '/usr/local/psa/var/modules/composer/composer.phar';
$npm_bin = '/opt/plesk/node/12/bin/npm';

switch ($environment) {
case 'prod':
$ssh_connection = 'cyc-trosthelden@116.202.53.50 -p 55555 -A';
$releases_dir = '/var/www/vhosts/trosthelden.de/httpdocs/app.trosthelden.de/releases';
$app_dir = '/var/www/vhosts/trosthelden.de/httpdocs/app.trosthelden.de';
break;
default:
$ssh_connection = 'cyc-trosthelden@116.202.53.50 -p 55555 -A';
$releases_dir = '/var/www/vhosts/trosthelden.de/httpdocs/staging.trosthelden.de/releases';
$app_dir = '/var/www/vhosts/trosthelden.de/httpdocs/staging.trosthelden.de';
}

$new_release_dir = $releases_dir . '/' . $release;
@endsetup

@servers(['server' => $ssh_connection])

@story('deploy')
clone_repo
install_deb
build
activate_release
clean_releases
@endstory

@task('clone_repo')
echo 'Cloning {{ $branch }}'
[ -d {{ $releases_dir }} ] || mkdir {{ $releases_dir }}
git clone -b {{ $branch }} --depth 1 {{ $repository }} {{ $new_release_dir }}
cd {{ $new_release_dir }}
git reset --hard {{ $commit }}
@endtask

@task('install_deb')
echo "Installing dependencies for Release ({{ $release }})"
cd {{ $new_release_dir }}
{{ $php_bin }} {{ $composer_bin }} config http-basic.nova.laravel.com {{ $nova_username }} {{ $nova_password }}
{{ $php_bin }} {{ $composer_bin }} install --prefer-dist --no-scripts -o -q
{{ $npm_bin }} install --ignore-scripts
@endtask

@task('build')
echo "Linking storage directory into release"
rm -rf {{ $new_release_dir }}/storage
ln -nfs {{ $app_dir }}/storage {{ $new_release_dir }}/storage

echo 'Linking .env file into release'
ln -nfs {{ $app_dir }}/.env {{ $new_release_dir }}/.env

cd {{ $new_release_dir }}
echo "Building JS & CSS files"
{{ $npm_bin }} run prod --scripts-prepend-node-path=true
echo "Migrating DB"
{{ $php_bin }} artisan migrate --force
echo "Migrations completed"
echo "beginn seeding DB"
{{ $php_bin }} artisan db:seed --force
echo "Seeding completed"
echo "Linking storage"
{{ $php_bin }} artisan storage:link
echo "Preparing cache"
{{ $php_bin }} artisan config:cache
@endtask

@task('activate_release')
echo 'Linking current release'
ln -nfs {{ $new_release_dir }} {{ $app_dir }}/current
@endtask

@task('clean_releases')
cd {{ $releases_dir }}
ls -1tr | head -n -{{ $versions_to_keep }} | xargs -d '\n' rm -rf --
@endtask
