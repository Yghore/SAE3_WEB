# SAE3_WEB
SAE DU WEB

## Base de donnée

User ()
```sql
id = integer(11)
email = varchar(25)
pass = varchar(255)
token = varchar(255)
```

Token ()
```sql
token = varchar(255)
iduser = integer(11)
```

favorite2user ()
```sql
iduser = integer(11)
idserie = integer(11)
```

current2user ()
```sql
iduser = integer(11)
idepisode = integer(11)
```