<?php

namespace Orchestra\Foundation\Http\Middleware;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CanRegisterUser extends Can
{
    /**
     * Check authorization.
     *
     * @param  string  $action
     *
     * @return bool
     */
    protected function authorize(?string $action = null): bool
    {
        return $this->foundation->memory()->get('site.registrable', false);
    }

    /**
     * Response on authorized request.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return mixed
     */
    protected function responseOnUnauthorized($request)
    {
        throw new NotFoundHttpException('User registration is not allowed.');
    }
}
