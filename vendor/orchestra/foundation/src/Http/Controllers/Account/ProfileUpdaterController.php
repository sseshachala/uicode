<?php

namespace Orchestra\Foundation\Http\Controllers\Account;

use Illuminate\Http\Request;
use Orchestra\Contracts\Foundation\Listener\Account\ProfileUpdater as Listener;
use Orchestra\Foundation\Processors\Account\ProfileUpdater as Processor;

class ProfileUpdaterController extends Controller implements Listener
{
    /**
     * Edit user account/profile page.
     *
     * GET (:orchestra)/account
     *
     * @param  \Orchestra\Foundation\Processors\Account\ProfileUpdater  $processor
     *
     * @return mixed
     */
    public function edit(Processor $processor)
    {
        return $processor->edit($this);
    }

    /**
     * POST Edit user account/profile.
     *
     * POST (:orchestra)/account
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Orchestra\Foundation\Processors\Account\ProfileUpdater  $processor
     *
     * @return mixed
     */
    public function update(Request $request, Processor $processor)
    {
        return $processor->update($this, $request->all());
    }

    /**
     * Response to show user profile changer.
     *
     * @param  array  $data
     *
     * @return mixed
     */
    public function showProfileChanger(array $data)
    {
        \set_meta('title', \trans('orchestra/foundation::title.account.profile'));

        return \view('orchestra/foundation::account.index', $data);
    }

    /**
     * Response when validation on update profile failed.
     *
     * @param  \Illuminate\Contracts\Support\MessageBag|array  $errors
     *
     * @return mixed
     */
    public function updateProfileFailedValidation($errors)
    {
        return $this->redirectWithErrors(\handles('orchestra::account'), $errors);
    }

    /**
     * Response when update profile failed.
     *
     * @param  array  $errors
     *
     * @return mixed
     */
    public function updateProfileFailed(array $errors)
    {
        $message = \trans('orchestra/foundation::response.db-failed', $errors);

        return $this->redirectWithMessage(\handles('orchestra::account'), $message, 'error');
    }

    /**
     * Response when update profile succeed.
     *
     * @return mixed
     */
    public function profileUpdated()
    {
        $message = \trans('orchestra/foundation::response.account.profile.update');

        return $this->redirectWithMessage(\handles('orchestra::account'), $message);
    }
}
