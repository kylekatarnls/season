<?php

class App
{
    public ?array $seasons = null;

    public function get($name)
    {
        return $name === 'config' ? $this : function ($app) {
            return $app->seasons;
        };
    }

    public function has($name)
    {
        return in_array($name, ['config', 'season'], true);
    }

    public function setSeasonsConfig($seasons): void
    {
        $this->seasons = $seasons;
    }

    public function singleton(string $className, callable $provider): void
    {
        // noop
    }
}
