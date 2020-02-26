<?php

namespace Orchestra\Notifier;

use Exception;
use Illuminate\Contracts\Mail\Mailable as MailableContract;
use Illuminate\Support\Fluent;
use Orchestra\Contracts\Notification\Message as MessageContract;

class Message extends Fluent implements MessageContract
{
    /**
     * Create a new Message instance.
     *
     * @param  \Illuminate\Contracts\Mail\Mailable|string|array  $view
     * @param  array  $data
     * @param  string|null  $subject
     *
     * @return static
     */
    public static function create($view, array $data = [], $subject = null)
    {
        return new static([
            'view' => $view,
            'data' => $data,
            'subject' => $subject,
        ]);
    }

    /**
     * Get data.
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->attributes['data'] ?? [];
    }

    /**
     * Get subject.
     *
     * @return string
     */
    public function getSubject(): string
    {
        return $this->attributes['subject'] ?? '';
    }

    /**
     * Get view.
     *
     * @return \Illuminate\Contracts\Mail\Mailable|string|array
     */
    public function getView()
    {
        if (! isset($this->attributes['view'])) {
            throw new Exception('Missing $view variable.');
        }

        return $this->attributes['view'];
    }

    /**
     * Is message mailable.
     *
     * @return bool
     */
    public function mailable()
    {
        if (! isset($this->attributes['view'])) {
            return false;
        }

        return $this->attributes['view'] instanceof MailableContract;
    }
}
