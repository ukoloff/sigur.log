# sigur.log
Mass export data from Sigur

## Dev environment

1) Allow SSH access from old clients
    ```
    # /etc/ssh/sshd_config.d/old.conf
    # C:\ProgramData\ssh\sshd_config

    #Match Group administrators
    #       AuthorizedKeysFile __PROGRAMDATA__/ssh/administrators_authorized_keys

    HostKeyAlgorithms +ssh-rsa
    PubkeyAcceptedKeyTypes  +ssh-rsa
    ```

2) Mount using [sshfs] on `net.ekb.ru`
    ```sh
    sshfs vasya@uxm00035:Documents/repo /home/stas/repo -o umask=222 -o allow_other
    ```

3) Umount
    ```sh
    umount /home/stas/repo
    ```

[sshfs]: https://github.com/libfuse/sshfs

3) Add symlinks *quantum satis*
