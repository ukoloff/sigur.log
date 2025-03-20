#!/bin/sh

# https://superuser.com/a/1361548/2436173
if test -n "$(find ./ -maxdepth 0 -empty)"
then
  git clone $GIT_REPO --branch $GIT_BRANCH .
fi

git pull --ff-only

exec tail -f /dev/null
