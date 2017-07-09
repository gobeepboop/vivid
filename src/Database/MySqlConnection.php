<?php

namespace Beep\Vivid\Database;

use Beep\Vivid\Database\Concerns\ResolvesBlueprint;
use Illuminate\Database\MySqlConnection as Base;

class MySqlConnection extends Base
{
    use ResolvesBlueprint;
}
