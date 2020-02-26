<?php

namespace Orchestra\Foundation\Processors\Account;

use Exception;
use Illuminate\Support\Facades\Auth;
use Orchestra\Contracts\Foundation\Command\Account\ProfileUpdater as Command;
use Orchestra\Contracts\Foundation\Listener\Account\ProfileUpdater as Listener;

class ProfileUpdater extends User implements Command
{
    /**
     * Get account/profile information.
     *
     * @param  \Orchestra\Contracts\Foundation\Listener\Account\ProfileUpdater  $listener
     *
     * @return mixed
     */
    public function edit(Listener $listener)
    {
        $user = Auth::user();

        $form = $this->presenter->profile($user, 'orchestra::account');

        $this->fireEvent('form', [$user, $form]);

        return $listener->showProfileChanger(['eloquent' => $user, 'form' => $form]);
    }

    /**
     * Update profile information.
     *
     * @param  \Orchestra\Contracts\Foundation\Listener\Account\ProfileUpdater  $listener
     * @param  array  $input
     *
     * @return mixed
     */
    public function update(Listener $listener, array $input)
    {
        $user = Auth::user();

        if (! $this->validateCurrentUser($user, $input)) {
            return $listener->abortWhenUserMismatched();
        }

        $validation = $this->validator->on('update')->with($input);

        if ($validation->fails()) {
            return $listener->updateProfileFailedValidation($validation->getMessageBag());
        }

        try {
            $this->saving($user, $input);
        } catch (Exception $e) {
            return $listener->updateProfileFailed(['error' => $e->getMessage()]);
        }

        return $listener->profileUpdated();
    }

    /**
     * Save user profile.
     *
     * @param  \Orchestra\Foundation\Auth\User  $user
     * @param  array  $input
     *
     * @return void
     */
    protected function saving($user, array $input)
    {
        $user->setAttribute('email', $input['email']);
        $user->setAttribute('fullname', $input['fullname']);

        $this->fireEvent('updating', [$user]);
        $this->fireEvent('saving', [$user]);

        $user->saveOrFail();

        $this->fireEvent('updated', [$user]);
        $this->fireEvent('saved', [$user]);
    }
}
