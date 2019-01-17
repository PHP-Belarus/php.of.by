#!/usr/bin/env sh
# We should deploy only build for php 7.3
if [ "${TRAVIS_PULL_REQUEST}" = "false" ] && [ $(phpenv version-name) = "7.3" ]; then
    eval "$(ssh-agent -s)"
    chmod 600 .travis/deploy.key
    ssh-add .travis/deploy.key
    vendor/bin/dep deploy production
fi
