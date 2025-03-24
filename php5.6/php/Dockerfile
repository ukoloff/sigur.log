FROM debian:bookworm-slim

LABEL org.opencontainers.image.authors="Stanislav.Ukolov@omzglobal.com"

RUN <<EOF
  apt-get update
  apt-get -y install lsb-release ca-certificates wget
  wget https://packages.sury.org/php/apt.gpg -O /usr/share/keyrings/deb.sury.org-php.gpg
  echo "deb [signed-by=/usr/share/keyrings/deb.sury.org-php.gpg] https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php-sury.list
  apt-get update
  for PCKG in cli fpm xdebug curl gd ldap mysql pgsql snmp sqlite3 sybase yaml
  do
    apt-get install -y php5.6-$PCKG
  done
  apt-get clean
  rm -rf /var/lib/apt/lists/*
EOF

CMD ["php-fpm5.6", "--nodaemonize"]
