<?php

namespace Orchestra\Control\Contracts\Commands;

interface Synchronizer
{
    /**
     * Re-sync administrator access control.
     *
     * @return void
     */
    public function handle();
}
