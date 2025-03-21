FROM alpine

LABEL maintainer="Stanislav.Ukolov@omzglobal.com"

RUN <<EOF
  apk add git
  rm /var/cache/apk/*
EOF

COPY bin/* /bin/

WORKDIR /repo

STOPSIGNAL SIGKILL

ENTRYPOINT [ "start.sh" ]
