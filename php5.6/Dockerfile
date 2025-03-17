FROM debian:bookworm-slim

LABEL org.opencontainers.image.authors="Stanislav.Ukolov@omzglobal.com"

RUN <<EOF
  apt-get update
  apt-get -y install lsb-release ca-certificates
  wget https://packages.sury.org/php/apt.gpg -O /usr/share/keyrings/deb.sury.org-php.gpg
  echo "deb [signed-by=/usr/share/keyrings/deb.sury.org-php.gpg] https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php-sury.list
  apt update
EOF