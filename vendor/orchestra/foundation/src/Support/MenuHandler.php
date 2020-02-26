<?php

namespace Orchestra\Foundation\Support;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Fluent;

abstract class MenuHandler
{
    /**
     * The foundation implementation.
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $container;

    /**
     * Menu configuration.
     *
     * @var array
     */
    protected $menu = [
        'id' => null,
        'position' => '*',
        'title' => null,
        'link' => '#',
        'icon' => null,
        'with' => [],
    ];

    /**
     * Name for the menu.
     *
     * @var string
     */
    protected $name;

    /**
     * List of childs menu.
     *
     * @var array
     */
    protected $childs = [];

    /**
     * Enable the menu.
     *
     * @var bool
     */
    protected $showToUser = true;

    /**
     * Menu instance.
     *
     * @var \Orchestra\Widget\Handlers\Menu
     */
    protected $handler;

    /**
     * Construct a new handler.
     *
     * @param  \Illuminate\Contracts\Container\Container  $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->handler = $container->make('orchestra.platform.menu');
    }

    /**
     * Create a handler.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->prepare();

        if ($this->showToUser === true) {
            $this->create();
        }
    }

    /**
     * Create a new menu.
     *
     * @return $this
     */
    public function create()
    {
        $menu = $this->handler->add($this->name, $this->getAttribute('position'))
                    ->title($this->getAttribute('title'))
                    ->link($this->getAttribute('link'))
                    ->handles($this->menu['link'] ?? null);

        $this->attachIcon($menu);
        $this->handleNestedMenu();

        return $this;
    }

    /**
     * Determine if the request passes the authorization check.
     *
     * @return bool
     */
    public function passes(): bool
    {
        return $this->showToUser;
    }

    /**
     * Handle get attributes.
     *
     * @param  string  $name
     *
     * @return mixed
     */
    public function getAttribute(string $name)
    {
        $method = 'get'.\ucfirst($name).'Attribute';
        $value = $this->menu[$name] ?? null;

        if (\method_exists($this, $method)) {
            return $this->container->call([$this, $method], ['value' => $value]);
        }

        return $value;
    }

    /**
     * Set attribute.
     *
     * @param  string  $name
     * @param  mixed   $value
     *
     * @return $this
     */
    public function setAttribute(string $name, $value)
    {
        if (\in_array($name, ['id'])) {
            $this->{$name} = $value;
        }

        $this->menu[$name] = $value;

        return $this;
    }

    /**
     * Get the URL.
     *
     * @param  string  $value
     *
     * @return string
     */
    public function getLinkAttribute($value)
    {
        return $this->container->make('orchestra.app')->handles($value);
    }

    /**
     * Prepare nested menu.
     *
     * @return $this
     */
    public function prepare()
    {
        $id = $this->getAttribute('id');
        $menus = $this->menu['with'] ?? [];
        $parent = $this->getAttribute('position');
        $position = \strpos($parent, '^:') === 0 ? $parent.'.' : '^:';

        foreach ((array) $menus as $class) {
            $menu = $this->container->make($class)
                            ->setAttribute('position', "{$position}{$id}")
                            ->prepare();

            if ($menu->passes()) {
                $this->childs[] = $menu;
            }
        }

        $this->name = $id;
        $this->showToUser = $this->passesAuthorization();

        return $this;
    }

    /**
     * Check if has any nested menu.
     *
     * @return bool
     */
    public function hasNestedMenu(): bool
    {
        return ! empty($this->childs);
    }

    /**
     * Attach icon to menu.
     *
     * @param  \Illuminate\Support\Fluent|null  $menu
     *
     * @return void
     */
    protected function attachIcon(Fluent $menu = null): void
    {
        if (! (\is_null($menu) || \is_null($icon = $this->getAttribute('icon')))) {
            $menu->icon($icon);
        }
    }

    /**
     * Attach nested menu.
     *
     * @return $this
     */
    protected function handleNestedMenu()
    {
        foreach ((array) $this->childs as $menu) {
            $menu->create();
        }

        return $this;
    }

    /**
     * Determine if the request passes the authorization check.
     *
     * @return bool
     */
    protected function passesAuthorization(): bool
    {
        $passes = false;

        if (\method_exists($this, 'authorize')) {
            $passes = $this->container->call([$this, 'authorize']);
        }

        return (bool) $passes;
    }

    /**
     *  Handle dynamic calls to the container to get attributes.
     *
     * @param  string  $name
     * @param  array   $parameters
     *
     * @return mixed
     */
    public function __call(string $name, array $parameters)
    {
        return $this->getAttribute($name);
    }
}
