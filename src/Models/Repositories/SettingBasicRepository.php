<?php
declare(strict_types=1);

namespace SP\Admin\Models\Repositories;

use SP\Admin\Contracts\Setting\AbstractBasicRepository;

/**
 * Basic setting repository.
 *
 * @package SP\Admin\Models\Repositories
 */
class SettingBasicRepository extends AbstractBasicRepository
{
    /**
     * {@inheritDoc}
     */
    public function modelDetailsConfig(): array
    {
        $model = $this->model;
        $data = $model->getAll();
        $app_logo_url = $this->appLogoUrlResized(['w' => 250]);

        return [
            [
                'label' => $model::getAttributeLabel('app_name'),
                'value' => $data['app_name'] ?? '-',
            ],
            [
                'label' => $model::getAttributeLabel('admin_lang'),
                'value' => rescue(static function () use (&$data): string {
                    return config('admin.languages')[$data['admin_lang']];
                }, '-', false),
            ],
            [
                'label' => $model::getAttributeLabel('app_logo'),
                'value' => rescue(static function () use (&$data, &$app_logo_url) {
                    if ($app_logo_url) {
                        return html()->img($app_logo_url)->class('img-thumbnail')->attributes(['alt' => '']);
                    }

                    return '-';
                }, '-', false),
            ],
            [
                'label' => $model::getAttributeLabel('timezone'),
                'value' => $data['timezone'] ?? '-',
            ],
            [
                'label' => $model::getAttributeLabel('mail_gate_host'),
                'value' => $data['mail_gate_host'] ?? '-',
            ],
            [
                'label' => $model::getAttributeLabel('mail_gate_login'),
                'value' => $data['mail_gate_login'] ?? '-',
            ],
            [
                'label' => $model::getAttributeLabel('mail_gate_password'),
                'value' => rescue(static function () use (&$data): string {
                    $len = \strlen((string)$data['mail_gate_password']);

                    return \str_repeat('*', $len);
                }, '-', false),
            ],
            [
                'label' => $model::getAttributeLabel('mail_gate_port'),
                'value' => $data['mail_gate_port'] ?? '-',
            ],
            [
                'label' => $model::getAttributeLabel('mail_gate_encryption'),
                'value' => rescue(static function () use (&$data): string {
                    return $this->mailEncryptionListForSelector()[$data['mail_gate_encryption']];
                }, '-', false),
            ],
        ];
    }

}
