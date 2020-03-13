<?php
declare(strict_types=1);

namespace SP\Admin\Models\Repositories;

use SP\Admin\Models\SettingScript;

/**
 * Client scripts repository.
 *
 * @package SP\Admin\Models\Repositories
 */
final class SettingScriptRepository
{
    /**
     * Config for modelDetails widget.
     *
     * @param SettingScript $model
     * @return array [["label" => "Setting name", "value" => "Setting value"]]
     * @throws \InvalidArgumentException
     */
    public function modelDetailsConfig(SettingScript $model): array
    {
        return [
            [
                'label' => $model::getAttributeLabel($model::HEAD),
                'value' => $model->get($model::HEAD),
            ],
            [
                'label' => $model::getAttributeLabel($model::BOTTOM),
                'value' => $model->get($model::BOTTOM),
            ],
        ];
    }

    /**
     * Script tag.
     *
     * @param SettingScript $model
     * @param string $position
     * @return string Html
     */
    public function getScriptTag(SettingScript $model, string $position): string
    {
        return '<script src="' . $model->getUrl($position) . '"></script>';
    }

}
