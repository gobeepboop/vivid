<?php

namespace Beep\Vivid\Schema;

use Illuminate\Database\Schema\Builder;
use Illuminate\Support\Fluent;

class Blueprint extends \Illuminate\Database\Schema\Blueprint
{
    /**
     * Create a new binary column on the table.
     *
     * @param  string  $column
     * @param  int  $length
     * @return Fluent
     */
    final public function binary($column, $length = null): Fluent
    {
        $length = $length ?: Builder::$defaultStringLength;

        return $this->addColumn('binary', $column, compact('length'));
    }

    /**
     * Create a new primary UUID BINARY(16)/CHAR(36) column on the table.
     *
     * @param bool $optimized
     *
     * @return Fluent
     */
    final public function randomizes(bool $optimized = true): Fluent
    {
        return $optimized ? $this->binary('id', 16) : $this->char('id', 36);
    }
}
