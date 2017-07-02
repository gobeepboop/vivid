Vivid
=====

Vivid is an expressive set of recipes to extend Laravel Eloquent. Features UUID key storage as BINARY(16) and 36 character strings.

# Installation
```
composer require beep/vivid
```

# Usage

## Optimized UUID4
```php
<?php

use Beep\Vivid\Database\Eloquent\Model;

class User extends Model
{
    
}
```
## UUID4

```
class User extends Model
{
    protected $optimizedUuid = false;
}
```

## ToDo
* Cleanup and expand tests both optimized and add non-optimized UUID Model tests.
* Grammar and expansion of the Schema Blueprint to add a `$table->binary()` column.
* Properly cast optimized UUIDs.
* JSON serialization by indicated columns for optimized.
