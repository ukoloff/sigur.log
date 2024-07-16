# sigur.log
Mass export data from Sigur

## Dev environment

1) Allow SSH access from old clients
    ```
    # /etc/ssh/sshd_config.d/old.conf
    # C:\ProgramData\ssh\sshd_config

    HostKeyAlgorithms +ssh-rsa
    PubkeyAcceptedKeyTypes  +ssh-rsa
    ```

2) Mount using [sshfs] on `net.ekb.ru`
    ```sh
    sshfs vasya@uxm00035:Documents/repo /home/stas/repo -o umask=222 -o allow_other
    ```

[sshfs]: https://github.com/libfuse/sshfs

3) Add symlinks *quantum satis*
