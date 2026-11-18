# Users management

### Adding users to MongoDB

Users can be added by invoking command `Command->createUser()`, which
takes as an argument `User` model which has properties compatible with
[mongodb params for `createUser`][create-user].

Define it's `User->user` (username), `User->pwd` (password),
and `User->roles`, then pass it to `Command->createUser()`.

Property `User->roles` can be defined as array of roles or as object
of type `Roles`, which can be used to simplify defining roles.

Example of creating user:

```
$user = new User;
$user->user = 'webuser';
$user->pwd = 'admin123';
$user->roles = new Roles('myDatabase', ['readWrite']);
```

Then save it with `Command->createUser()`:

```
(new Command)->createUser($user);
```

### Removing users

To remove user invoke `Command->dropUser()`, with it's username as an
argument.

Example of removing user:

```
(new Command)->dropUser('webuser');
```

[create-user]: https://docs.mongodb.com/manual/reference/method/db.createUser/
