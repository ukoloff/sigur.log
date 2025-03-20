FROM alpine/git:latest

LABEL maintainer="Stanislav.Ukolov@omzglobal.com"

COPY bin/* /bin/

WORKDIR /repo

STOPSIGNAL SIGKILL

ENTRYPOINT [ "start.sh" ]
