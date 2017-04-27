<?php

use Maslosoft\Mangan\Command;
use Maslosoft\Mangan\Mangan;
use Maslosoft\Zamm\ShortNamer;
?>
<?php
ShortNamer::defaults()->md();
/* @var $mangan Mangan */
/* @var $command Command */
$mangan = new ShortNamer(Mangan::class);
$command = new ShortNamer(Command::class);
?>
<title>1. Configuration</title>
#Configuration

### Connecting to MongoDB

By default Mangan will connect to MongoDB on `localhost`, port `27017`. This can
be customized by setting property <?= $mangan->connectionString; ?>.

Connection string must start with `mongodb://` followed by server connection
data in form of `username:password@db-host:port`, for example:

> mongodb://root:admin123@example.com:27018

Only required connection string part is it's host. When other parameters
are not specified, they will be substituted with default values:

* Empty username
* Empty password
* 27017 port

#### Connecting to multiple servers

To connect Mangan [to replica set][replica-set] separate multiple servers
with coma:

> mongodb://root:admin123@example.com:27018,root:admin123@example.com:27018

This is minimal configuration for replication.

#### Checking connection

To test if Mangan can connect to any of the servers, <?= $command->ping(); ?>
command can be used, this will return array containing success or error message:

```
['ok' => 1]
```

This result is equivalent of calling native [MongoDB ping command][ping]

Note that <?= $command->ping(); ?> will not authenticate, but will indicate
whether it is possible to connect.


[replica-set]: https://docs.mongodb.com/manual/replication/
[ping]: https://docs.mongodb.com/manual/reference/command/ping/