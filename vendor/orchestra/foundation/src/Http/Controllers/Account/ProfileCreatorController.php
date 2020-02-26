<?php

namespace Orchestra\Foundation\Http\Controllers\Account;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Orchestra\Contracts\Foundation\Listener\Account\ProfileCreator as Listener;
use Orchestra\Foundation\Concerns\RedirectUsers;
use Orchestra\Foundation\Http\Controllers\AdminController;
use Orchestra\Foundation\Processors\Account\ProfileCreator as Processor;

class ProfileCreatorController extends AdminController implements Listener
{
    use RedirectUsers;

    /**
     * Setup controller middleware.
     *
     * @return void
     */
    protected function onCreate()
    {
        $this->middleware('orchestra.registrable');
    }

    /**
     * User Registration Page.
     *
     * GET (:orchestra)/register
     *
     * @param  \Orchestra\Foundation\Processors\Account\ProfileCreator  $processor
     *
     * @return mixed
     */
    public function create(Processor $processor)
    {
        return $processor->create($this);
    }

    /**
     * Create a new user.
     *
     * POST (:orchestra)/register
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Orchestra\Foundation\Processors\Account\ProfileCreator  $processor
     *
     * @return mixed
     */
    public function store(Request $request, Processor $processor)
    {
        return $processor->store($this, $request->all());
    }

    /**
     * Response when show registration page succeed.
     *
     * @param  array  $data
     *
     * @return mixed
     */
    public function showProfileCreator(array $data)
    {
        \set_meta('title', \trans('orchestra/foundation::title.register'));

        return \view('orchestra/foundation::credential.register', $data);
    }

    /**
     * Response when create a user failed validation.
     *
     * @param  \Illuminate\Contracts\Support\MessageBag|array  $errors
     *
     * @return mixed
     */
    public function createProfileFailedValidation($errors)
    {
        return $this->redirectWithErrors($this->getRedirectToRegisterPath(), $errors);
    }

    /**
     * Response when create a user failed.
     *
     * @param  array  $errors
     *
     * @return mixed
     */
    public function createProfileFailed(array $errors)
    {
        \messages('error', \trans('orchestra/foundation::response.db-failed', $errors));

        return $this->redirect($this->getRedirectToRegisterPath())->withInput();
    }

    /**
     * Response when create a user succeed but unable to notify the user.
     *
     * @return mixed
     */
    public function profileCreatedWithoutNotification()
    {
        \messages('success', \trans('orchestra/foundation::response.users.create'));
        \messages('error', \trans('orchestra/foundation::response.credential.register.email-fail'));

        return Redirect::intended($this->getRedirectToLoginPath());
    }

    /**
     * Response when create a user succeed with notification.
     *
     * @return mixed
     */
    public function profileCreated()
    {
        \messages('success', \trans('orchestra/foundation::response.users.create'));
        \messages('success', \trans('orchestra/foundation::response.credential.register.email-send'));

        return Redirect::intended($this->getRedirectToLoginPath());
    }

    /**
     * Get redirect to register path.
     *
     * @param  string|null  $redirect
     *
     * @return string
     */
    protected function getRedirectToRegisterPath($redirect = null)
    {
        return $this->redirectUserTo('register', 'orchestra::register', $redirect);
    }

    /**
     * Get redirect to login path.
     *
     * @param  string|null  $redirect
     *
     * @return string
     */
    protected function getRedirectToLoginPath($redirect = null)
    {
        return $this->redirectUserTo('login', 'orchestra::login', $redirect);
    }
}
