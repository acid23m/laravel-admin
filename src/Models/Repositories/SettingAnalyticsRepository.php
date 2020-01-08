<?php
declare(strict_types=1);

namespace SP\Admin\Models\Repositories;

use SP\Admin\Models\SettingAnalytics;

/**
 * Class SettingAnalyticsRepository.
 *
 * @package SP\Admin\Models\Repositories
 */
final class SettingAnalyticsRepository
{
    /**
     * Config for modelDetails widget.
     *
     * @param SettingAnalytics $model
     * @return array [["label" => "Setting name", "value" => "Setting value"]]
     */
    public function modelDetailsConfig(SettingAnalytics $model): array
    {
        $data = $model->getAll();

        return [
            [
                'label' => $model::getAttributeLabel('google'),
                'value' => $data['google'] ?? '-',
            ],
            [
                'label' => $model::getAttributeLabel('yandex'),
                'value' => $data['yandex'] ?? '-',
            ],
        ];
    }

    /**
     * Registers google analytics.
     *
     * @param SettingAnalytics $model
     * @return string
     */
    public function registerGoogleAnalytics(SettingAnalytics $model): string
    {
        if (config('app.debug')) {
            return '';
        }

        $id = $model->get('google');

        if (blank($id)) {
            return '';
        }

        return <<<CODE
<link rel="dns-prefetch" href="//www.googletagmanager.com">

<script async src="https://www.googletagmanager.com/gtag/js?id=$id"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', '$id');
</script>
CODE;
    }

    /**
     * Registers yandex metrika.
     *
     * @param SettingAnalytics $model
     * @return string
     */
    public function registerYandexMetrika(SettingAnalytics $model): string
    {
        if (config('app.debug')) {
            return '';
        }

        $id = $model->get('yandex');

        if (blank($id)) {
            return '';
        }

        return <<<CODE
<link rel="dns-prefetch" href="//mc.yandex.ru">

<script>
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function () {
            try {
                w.yaCounter{$id} = new Ya.Metrika2({
                    id: $id,
                    clickmap: true,
                    trackLinks: true,
                    accurateTrackBounce: true,
                    webvisor: true
                });
            } catch(e) { }
        });

        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () { n.parentNode.insertBefore(s, n); };
        s.type = "text/javascript";
        s.async = true;
        s.src = "https://mc.yandex.ru/metrika/tag.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else { f(); }
    })(document, window, "yandex_metrika_callbacks2");
</script>
<noscript>
    <div><img src="https://mc.yandex.ru/watch/$id" style="position:absolute; left:-9999px;" alt="" /></div>
</noscript>
CODE;
    }

}
