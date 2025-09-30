<?php

namespace FabianMichael\ClickToPlay;

use FabianMichael\ClickToPlay\Providers\Provider;
use FabianMichael\ClickToPlay\Providers\Vimeo;
use FabianMichael\ClickToPlay\Providers\YouTube;

class Embed
{
    protected $providerId;
	protected $provider;
    protected $name;

    public static $providers = [
        'youtube' => YouTube::class,
        'vimeo'   => Vimeo::class,
    ];

    public static function fromUrl(string $url): ?Embed
    {
        foreach (static::$providers as $name => $class) {
            $provider = $class::factory($url);

            if ($provider !== null) {
                return new static($provider, $name);
            }
        }

        return null;
    }

    protected function __construct(Provider $provider, string $providerId)
    {
        $this->provider = $provider;
        $this->providerId = $providerId;
    }

    /**
     * Magic caller for provider methods
     *
     * @param string $method
     * @param array $arguments
     * @return mixed
     */
    public function __call(string $method, array $arguments = [])
    {
        // provider method proxy
        if (method_exists($this->provider, $method)) {
            return $this->provider->$method(...$arguments);
        }

        // provider data access
        return $this->provider->data($method);
    }

    public function providerId(): string
    {
        return $this->providerId;
    }

    public function html(): string
    {
        return snippet('clicktoplay', ['embed' => $this], true);
    }
}
