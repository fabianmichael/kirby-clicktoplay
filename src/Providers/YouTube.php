<?php

namespace FabianMichael\ClickToPlay\Providers;

class YouTube extends Provider
{
	public const URL_SCHEMES = [
		'youtube.com\/embed\/([a-zA-Z0-9_-]+)',
		'youtube-nocookie.com\/embed\/([a-zA-Z0-9_-]+)',
		'youtube-nocookie.com\/watch\?v=([a-zA-Z0-9_-]+)',
		'v=([a-zA-Z0-9_-]+)',
		'youtu.be\/([a-zA-Z0-9_-]+)',
		'youtube.com\/shorts\/([a-zA-Z0-9_-]+)',
	];

	public function url(): string
	{
		return "https://www.youtube.com/embed/{$this->id()}?iv_load_policy=3&color=white&rel=0&showinfo=0&enablejsapi=1&autoplay=1";
	}

	public static function factory(string $url): ?Provider
	{
		if (preg_match('!youtu!i', $url) !== 1) {
			return null;
		}

		foreach (static::URL_SCHEMES as $schema) {
			if (preg_match("!{$schema}!i", $url, $matches)) {
				return new static($matches[1]);

				break;
			}
		}

    return null;
	}

	public function providerName(): string
	{
		return 'YouTube';
	}

	public function providerUrl(): string
	{
		return 'https://www.youtube.com';
	}

	public function providerId(): string
	{
		return 'youtube';
	}

	public function oembedUrl(): string
	{
		return "https://www.youtube.com/oembed?url=https://www.youtube.com/watch?v={$this->id()}&format=json";
	}

	public static function privacyUrl(): string
	{
		return 'https://www.youtube.com/static?template=terms';
	}
}
