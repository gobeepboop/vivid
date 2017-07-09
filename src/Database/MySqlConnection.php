<?php

namespace Beep\Vivid\Database;

use Beep\Vivid\Database\Concerns\ResolvesBlueprint;
use Beep\Vivid\Database\Schema\Grammars\MySqlGrammar;
use Illuminate\Database\MySqlConnection as Base;

class MySqlConnection extends Base
{
    use ResolvesBlueprint;

    /**
     * {@inheritdoc}
     */
    protected function getDefaultSchemaGrammar()
    {
        return $this->withTablePrefix(new MySqlGrammar);
    }
}
