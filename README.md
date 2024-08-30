# sigur.log
Mass export data from Sigur

## Dev environment

Access:
- [Development]
- [Production]

[Development]: https://nc.ekb.ru/omz/service/sgr/
[Production]: https://nc.ekb.ru/omz/service/sigur/


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

4) Add symlinks *quantum satis*

## Схема базы данных Sigur

### Таблица `td-db-main`.`PERSONAL`

Пользователи + Подразделения

- `USER_ENABLED` Оператор
- `USER_T_SSPILOGIN` Логин через AD
- `EXTID` = AD.`Object-Guid`
- `USER_DEPSRESTRICTION` Ограничить доступ к отделам
- `USER_T_REPORTS` Доступ к вкладке "Отчёты"

### Таблица `td-db-main`.`REPORTUSERDEP`

Подразделения, по которым доступны отчёты

- `USER_ID`
- `EMP_ID` - оба ссылаются на `PERSONAL`.`ID`

### Таблица `td-db-main`.`SIDEPARAMVALUES`

Дополнительные колонки

- `OBJ_ID` = `PERSONAL`.`ID` пользователя
- `TABLE_ID` = 0
- `PARAM_IDX` = 6
- `VALUE` Уволен

### Таблица `td-db-main`.`SIDEPARAMTYPES`

Описания доп. колонок

|PARAM_IDX|ORDER_IDX|NAME|TYPE|
|---------|---------|----|----|
|1|0|id_lenel|STRING|
|3|4|Уволен по линии СБ|BOOL|
|4|1|Начальник отдела|BOOL|
|5|3|Дата Увольнения|DATE|
|6|2|Уволен|BOOL|

### Таблица `tc-db-log`.`logs`

Проходы и прочие события

- `EMPHINT` = `PERSONAL`.`ID` пользователя
- `DEVHINT` = `DEVICES`.`ID`
- `LOGDATA` BLOB
    + [1:2] = 0xFE06 проход
    + [5]
      * `1` Выход
      * `2` Вход

## Настройки клиента Sigur

Настройки клиента Sigur,
включая сервер для подключения,
хранятся в %USERPROFILE%/.tcgui.cfg
