<?php

use Kirby\Toolkit\Str;

$class = $class ?? null;

?>

<div
  class="c-clicktoplay frame [ js-clicktoplay ]"
  style="--aspect-ratio: 16 / 9"
  data-provider="<?= $embed->providerId() ?>"
>
  <div class="c-clicktoplay__consent theme--dark text-white">
    <?php if ($poster = $embed->poster()): ?>
      <img
        src="<?= $poster->url() ?>"
        loading="lazy"
        class="c-clocktoplay__poster"
        alt="<?= $embed->title()->html() ?>">
    <?php endif ?>
    <div class="prose text-center text-xs">
        <p>
        <?= Str::template('Mit dem Laden des Videos akzeptierst du die {{ linkOpen }}Datenschutzerklärung von {{ providerName }}{{ linkClose }}.', [
            'providerName' => $embed->providerName(),
            'linkOpen' => '<a href="'. $embed->privacyUrl() .'" target="_blank" rel="noopener noreferrer">',
            'linkClose' => '</a>'
        ]) ?>
        </p>
        <p>
          【
          <button 
              class="inline ghost-link"
              aria-label="Video laden und abspielen"
              onclick="var ctp=this.closest('.js-clicktoplay'),fr=ctp.querySelector('iframe'),cnsnt=this.parentNode;fr.contentWindow.location.replace(fr.dataset.src);fr.removeAttribute('tabindex');cnsnt.hidden=true;"
          >
            Video laden
          </button>
          】
        </p>
    </div>
  </div>
  <iframe
    src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
    data-src="<?= $embed->url() ?>"
    allow="<?= $embed->allow() ?>"
    allowfullscreen
    tabindex="-1"></iframe>
</div>
