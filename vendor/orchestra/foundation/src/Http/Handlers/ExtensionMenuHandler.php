<?php

namespace Orchestra\Foundation\Http\Handlers;

use Orchestra\Contracts\Authorization\Authorization;
use Orchestra\Foundation\Support\MenuHandler;

class ExtensionMenuHandler extends MenuHandler
{
    /**
     * Menu configuration.
     *
     * @var array
     */
    protected $menu = [
        'id' => 'extensions',
        'position' => '>:home',
        'title' => 'orchestra/foundation::title.extensions.list',
        'link' => 'orchestra::extensions',
        'icon' => 'cubes',
    ];

    /**
     * Get the title.
     *
     * @param  string  $value
     *
     * @return string
     */
    public function getTitleAttribute($value)
    {
        return \trans($value);
    }

    /**
     * Check authorization to display the menu.
     *
     * @param  \Orchestra\Contracts\Authorization\Authorization  $acl
     *
     * @return bool
     */
    public function authorize(Authorization $acl)
    {
        return $this->container->bound('orchestra.extension') && $acl->canIf('manage-orchestra');
    }
}
