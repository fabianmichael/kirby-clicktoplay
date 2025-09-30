<?php

namespace FabianMichael\ClickToPlay\Providers;

use Exception;
use Kirby\Content\Field;
use Kirby\Filesystem\Asset;
use Kirby\Filesystem\Dir;
use Kirby\Http\Remote;

abstract class Provider
{
	protected $data = false;
	protected $id;

	abstract public static function factory(string $url): ?Provider;

	protected function __construct(string $id)
	{
		$this->id = $id;
		$this->data = $this->oembedData();
	}

	public function data(?string $key = null)
	{
		if ($key !== null) {
			return new Field(null, $key, $this->data[$key] ?? null);
		}

		return $this->data;
	}

	public function poster(): ?Asset
	{
		$posterUrl = $this->data['thumbnail_url'] ?? null;

		if ($posterUrl === null) {
			return null;
		}

		$mediaRoot = kirby()->root('media');
		$mediaUrl = kirby()->url('media');
		$extension = pathinfo(parse_url($posterUrl, PHP_URL_PATH), PATHINFO_EXTENSION);
		$root = "{$mediaRoot}/clicktoplay/{$this->cacheKey()}/poster.{$extension}";
		$url = "{$mediaUrl}/clicktoplay/{$this->cacheKey()}/poster.{$extension}";

		if (file_exists($root) === false) {
			$curl = curl_init($posterUrl);

			Dir::make(dirname($root));
			$fp = fopen($root, 'w');

			curl_setopt($curl, CURLOPT_FILE, $fp);
			curl_setopt($curl, CURLOPT_HEADER, 0);

			curl_exec($curl);
			curl_close($curl);
			fclose($fp);
		}

		$path = str_replace(kirby()->url('index') . '/', '', $url);

		return new Asset($path);
	}

	abstract public function url(): string;

	abstract public static function privacyUrl(): string;

	public function id(): string
	{
		return $this->id;
	}

	abstract public function oembedUrl(): string;

	protected function cacheKey(): string
	{
		return "{$this->providerId()}-{$this->id()}";
	}

	abstract public function providerName(): string;

	abstract function providerUrl(): string;

	abstract public function providerId(): string;

	public function oembedData(): ?array
	{
		$cache = kirby()->cache('FabianMichael.clicktoplay');
		$key = $this->cacheKey();
		$data = $cache->get($key);

		if ($data === null) {
			try {
				$request = Remote::get($this->oembedUrl());

				if ($request->code() === 200) {
					$data = json_decode($request->content(), true);

					if ($data) {
						$cache->set($key, $data, option('FabianMichael.clicktoplay.cacheLifetime', 60 * 24));
					}
				}
			} catch (Exception $e) {
				throw new Exception('Clould not fetch oembed JSON response from provider.');
			};
		}

		return $data;
	}

	public function allow(): string
	{
		return 'autoplay; encrypted-media; picture-in-picture; fullscreen';
	}

	public function __call(string $method, array $arguments)
	{
		return isset($this->data[$method]) ? $this->data[$method] : null;
	}
}
