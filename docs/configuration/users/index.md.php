<?php

use Maslosoft\Mangan\Command;
use Maslosoft\Mangan\Mangan;
use Maslosoft\Mangan\Model\Command\Roles;
use Maslosoft\Mangan\Model\Command\User;
use Maslosoft\Zamm\ShortNamer;
?>
<?php
ShortNamer::defaults()->md();
/* @var $mangan Mangan */
/* @var $command Command */
/* @var $user User */
/* @var $roles User */
$mangan = new ShortNamer(Mangan::class);
$command = new ShortNamer(Command::class);
$user = new ShortNamer(User::class);
$roles = new ShortNamer(Roles::class);
?>
<title>1. Users</title>
#Users management

### Adding users to MongoDB

Users can be added by invoking command <?= $command->createUser(); ?>, which
takes as an argument <?= $user; ?> model which has properties compatible with
[mongodb params for `createUser`][create-user].

Define it's <?= $user->user; ?> (username), <?= $user->pwd; ?> (password),
and <?= $user->roles; ?>, then pass it to <?= $command->createUser(); ?>.

Property <?= $user->roles; ?> can be defined as array of roles or as object
of type <?= $roles; ?>, which can be used to simplify defining roles.

Example of creating user:

```
$user = new User;
$user->user = 'webuser';
$user->pwd = 'admin123';
$user->roles = new Roles('myDatabase', ['readWrite']);
```

Then save it with <?= $command->createUser(); ?>:

```
(new Command)->createUser($user);
```

### Removing users

To remove user invoke <?= $command->dropUser(); ?>, with it's username as an
argument.

Example of removing user:

```
(new Command)->dropUser('webuser');
```

[create-user]: https://docs.mongodb.com/manual/reference/method/db.createUser/