# Push

Synchronize Windows folder *to*
(outdated) Linux host

## Не используйте

Вместо этого вклчите
на Linux
и сервер SSH
на Windows,
см. [подробности](../README.md)

```sh
# mount
sshfs vasya@uxm00035:Documents/repo /home/stas/repo -o umask=222 -o allow_other -o reconnect

# umount
umount /home/stas/repo
```

```
# C:\ProgramData\ssh\sshd_config

#Match Group administrators
#       AuthorizedKeysFile __PROGRAMDATA__/ssh/administrators_authorized_keys

HostKeyAlgorithms +ssh-rsa
PubkeyAcceptedKeyTypes  +ssh-rsa
```
