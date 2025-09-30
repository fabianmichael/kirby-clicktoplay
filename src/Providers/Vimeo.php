<?php

namespace FabianMichael\ClickToPlay\Providers;

class Vimeo extends Provider
{
	public const URL_SCHEMES = [
		'vimeo.com\/([0-9]+)',
		'player.vimeo.com\/video\/([0-9]+)',
	];

	public function url(): string
	{
		return "https://player.vimeo.com/video/{$this->id()}?color=ffffff&title=0&byline=0&portrait=0&autoplay=true";
	}

	public static function factory(string $url): ?Provider
	{
		if (preg_match('!vimeo!i', $url) !== 1) {
			return null;
		}

		foreach (static::URL_SCHEMES as $schema) {
			if (preg_match("!$schema!i", $url, $matches)) {
				return new static($matches[1]);

				break;
			}
		}

    return null;
	}

	public function providerName(): string
	{
		return 'Vimeo';
	}

	public function providerUrl(): string
	{
		return 'https://vimeo.com';
	}

	public function providerId(): string
	{
		return 'vimeo';
	}

	public function oembedUrl(): string
	{
		return "https://vimeo.com/api/oembed.json?url=https://vimeo.com/{$this->id()}&width=800&height=450";
	}

	public static function privacyUrl(): string
	{
		return 'https://vimeo.com/privacy';
	}
}
