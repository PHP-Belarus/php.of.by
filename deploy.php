<?php
namespace Deployer;

require 'recipe/symfony.php';

// Project name
set('application', 'php.of.by');

// Shared files/dirs between deploys
set('shared_files', []);
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server 
add('writable_dirs', []);
set('allow_anonymous_stats', false);
set('writable_mode', 'chmod');

// Hosts
host(getenv('DEPLOY_HOST'))
    ->set('labels', ['stage' => 'production'])
    ->set('remote_user', getenv('DEPLOY_USER'))
    ->set('identity_file', '.travis/deploy.key')
    ->set('deploy_path', getenv('DEPLOY_PATH'));

// User for setting proper permissions
set('http_user', getenv('DEPLOY_USER'));
set('http_group', getenv('DEPLOY_USER'));

task('deploy:package_upload', function(){
    // Upload code package to server
    upload('package.tgz', sprintf('%s/package.tgz', get('deploy_path')));
})->desc('Upload code package to server');

task('deploy:package_extract', function() {
    $deployPath = get('deploy_path');
    run(sprintf('/bin/tar -xzf %s/package.tgz -C %s', $deployPath, get('release_path')));
    // Store package with release
    run(sprintf('/bin/mv %s/package.tgz %s', $deployPath, get('release_path')));
})->desc('Extract package');

// Tasks

desc('Deploy project');
task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:package_upload',
    'deploy:package_extract',
    'deploy:shared',
    'deploy:writable',
    'deploy:cache:clear',
    'deploy:publish',
]);

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

before('deploy:symlink', 'database:migrate');

