<?php

namespace Orchestra\Foundation\Processors;

use Orchestra\Contracts\Auth\Command\DeauthenticateUser as Command;
use Orchestra\Contracts\Auth\Listener\DeauthenticateUser as Listener;

class DeauthenticateUser extends Authenticate implements Command
{
    /**
     * Logout a user.
     *
     * @param  \Orchestra\Contracts\Auth\Listener\DeauthenticateUser  $listener
     *
     * @return mixed
     */
    public function logout(Listener $listener)
    {
        $this->auth->logout();

        return $listener->userHasLoggedOut();
    }

    /**
     * Logout a user.
     *
     * @param  \Orchestra\Contracts\Auth\Listener\DeauthenticateUser  $listener
     *
     * @return mixed
     */
    public function __invoke(Listener $listener)
    {
        return $this->logout($listener);
    }
}
