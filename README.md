# Import Query Generator

An efficient query generator for mass resource import, **distinguishing between new records and records to update**. This library uses MySQL's `ON DUPLICATE KEY UPDATE` feature.

## Preface

I highly recommend you at least skim through [my blog about this library](https://kfirba.me/blog/performant-mass-update-or-create-strategy-for-data-imports) to get a better understanding of this library.

##  Installation

You can add this library as a local, per-project dependency to your project using [Composer](https://getcomposer.org/):

```bash
composer require kfirba/import-query-generator
```

## Usage

```php
use Kfirba\QueryGenerator;

$table = 'users';
$data = [
    ['name' => 'John', 'email' => 'john@example.com', 'password' => 'hashed_password', 'created_at' => date('Y-m-d'), 'updated_at' => date('Y-m-d')],
    ['name' => 'Jane', 'email' => 'jane@example.com', 'password' => 'hashed_password', 'created_at' => date('Y-m-d'), 'updated_at' => date('Y-m-d')],
    ['name' => 'Susy', 'email' => 'susy@example.com', 'password' => 'hashed_password', 'created_at' => date('Y-m-d'), 'updated_at' => date('Y-m-d')],
];
$excludedColumnsFromUpdate = ['password', 'created_at'];

$queryObject = (new QueryGenerator)->generate($table, $data, $excludedColumnsFromUpdate);

$queryObject->getQuery();
// -> "insert into `users` (`name`,`email`,`password`,`created_at`,`updated_at`) values (?,?,?,?,?),(?,?,?,?,?),(?,?,?,?,?) on duplicate key update `name`=VALUES(`name`),`email`=VALUES(`email`),`updated_at`=VALUES(`updated_at`)"

$queryObject->getBindings();
// -> ['John', 'john@example.com', 'hashed_password', '2018-01-12', '2018-01-12', 'Jane', 'jane@example.com', 'hashed_password', '2018-01-12', '2018-01-12', 'Susy', 'Susy@example.com', 'hashed_password', '2018-01-12', '2018-01-12']
```

As you may have noticed, the generator defaults to `column=VALUES(column)` since this is usually what we use when we attempt to bulk import some data.
Need another behavior? You can submit a PR or just [open an issue](https://github.com/kfirba/import-query-generator/issues/new) and we can talk about it 🤓.

## License
This package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).