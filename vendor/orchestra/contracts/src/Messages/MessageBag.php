<?php

namespace Orchestra\Contracts\Messages;

use Closure;
use Illuminate\Contracts\Support\MessageBag as MessageBagContract;

interface MessageBag extends MessageBagContract
{
    /**
     * Extend Messages instance from session.
     *
     * @param  \Closure  $callback
     *
     * @return \Illuminate\Contracts\Support\MessageBag
     */
    public function extend(Closure $callback): MessageBagContract;

    /**
     * Retrieve Message instance from Session, the data should be in
     * serialize, so we need to unserialize it first.
     *
     * @return \Illuminate\Contracts\Support\MessageBag
     */
    public function copy(): MessageBagContract;

    /**
     * Store current instance.
     *
     * @return void
     */
    public function save(): void;
}
