<?php

namespace Beep\Vivid\Database\Schema\Grammars;

use Illuminate\Database\Schema\Grammars\MySqlGrammar as Base;
use Illuminate\Support\Fluent;

class MySqlGrammar extends Base
{
    /**
     * {@inheritdoc}
     */
    public function typeBinary(Fluent $column): string
    {
        return "binary";
    }
}
