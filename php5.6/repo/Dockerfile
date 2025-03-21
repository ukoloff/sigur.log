FROM alpine/git as git

FROM scratch

LABEL maintainer="Stanislav.Ukolov@omzglobal.com"

COPY --from=git / /
COPY bin/* /bin/

WORKDIR /repo

STOPSIGNAL SIGKILL

ENTRYPOINT [ "start.sh" ]
