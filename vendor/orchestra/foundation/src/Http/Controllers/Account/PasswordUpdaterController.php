<?php

namespace Orchestra\Foundation\Http\Controllers\Account;

use Illuminate\Http\Request;
use Orchestra\Contracts\Foundation\Listener\Account\PasswordUpdater as Listener;
use Orchestra\Foundation\Processors\Account\PasswordUpdater as Processor;

class PasswordUpdaterController extends Controller implements Listener
{
    /**
     * Edit change password page.
     *
     * GET (:orchestra)/account/password
     *
     * @param  \Orchestra\Foundation\Processors\Account\PasswordUpdater  $processor
     *
     * @return mixed
     */
    public function edit(Processor $processor)
    {
        return $processor->edit($this);
    }

    /**
     * POST Edit change password.
     *
     * POST (:orchestra)/account/password
     *
     * @param \Illuminate\Http\Request  $request
     * @param  \Orchestra\Foundation\Processors\Account\PasswordUpdater  $processor
     *
     * @return mixed
     */
    public function update(Request $request, Processor $processor)
    {
        return $processor->update($this, $request->all());
    }

    /**
     * Response to show user password.
     *
     * @param  array  $data
     *
     * @return mixed
     */
    public function showPasswordChanger(array $data)
    {
        \set_meta('title', \trans('orchestra/foundation::title.account.password'));

        return \view('orchestra/foundation::account.password', $data);
    }

    /**
     * Response when validation on change password failed.
     *
     * @param  \Illuminate\Contracts\Support\MessageBag|array  $errors
     *
     * @return mixed
     */
    public function updatePasswordFailedValidation($errors)
    {
        return $this->redirectWithErrors(\handles('orchestra::account/password'), $errors);
    }

    /**
     * Response when verify current password failed.
     *
     * @return mixed
     */
    public function verifyCurrentPasswordFailed()
    {
        $message = \trans('orchestra/foundation::response.account.password.invalid');

        return $this->redirectWithMessage(\handles('orchestra::account/password'), $message, 'error');
    }

    /**
     * Response when update password failed.
     *
     * @param  array  $errors
     *
     * @return mixed
     */
    public function updatePasswordFailed(array $errors)
    {
        $message = \trans('orchestra/foundation::response.db-failed', $errors);

        return $this->redirectWithMessage(\handles('orchestra::account/password'), $message, 'error');
    }

    /**
     * Response when update password succeed.
     *
     * @return mixed
     */
    public function passwordUpdated()
    {
        $message = \trans('orchestra/foundation::response.account.password.update');

        return $this->redirectWithMessage(\handles('orchestra::account/password'), $message);
    }
}
