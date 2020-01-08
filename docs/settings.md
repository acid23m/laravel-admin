Settings
========

The Admin panel has module for client-editable application settings.

Basic settings
--------------

It is predefined implementation for basic setting (site name, smtp config ...).
All attributes stores in `database/basic.settings.ini` file.
Do not forget to add it to your `.gitignore` file,
cause it may contains sensitive data.

To access this settings use `basic_settings()` global function.
Without parameters it returns all settings as array.
You can get the certain setting specifying a key.

```php
$resent_news_number = basic_settings('news_in_widget', 3);
```

### Extending

Of cause you will need additional settings for your site.

1. Create model and define it in `config/admin.php` in `settings` section as `basic_class`.
You can create completely new model extending it from [AbstractBasic](./src/Contracts/Setting/AbstractBasic.php) class.
But you can simply extend it from existing [SettingBasic](./src/Models/SettingBasic.php) model.

```php
<?php
declare(strict_types=1);

namespace App\Models;

use SP\Admin\Models\SettingBasic;

/**
 * Class MySettingBasic.
 *
 * @property string $recaptcha_sitekey
 * @property string $recaptcha_secret
 *
 * @package App\Models
 */
final class MySettingBasic extends SettingBasic
{
    /**
     * {@inheritDoc}
     */
    public static function attributeLabels(): array
    {
        $labels = [
            'recaptcha_sitekey' => 'Google Recaptcha sitekey',
            'recaptcha_secret' => 'Google Recaptcha secret',
        ];
        
        return \array_merge(parent::attributeLabels(), $labels);
    }

}
```

2. Edit `resources/views/vendor/admin/settings/basic/_form.blade.php` view file.

```
{{ html()->modelForm($model->getAll(), $method, $route)->open() }}

{{--other fields--}}

<br>
<h4>Google Recaptcha</h4>

<div class="form-row">
    <div class="form-group col-md-6">
        {{ html()->label($model::getAttributeLabel('recaptcha_sitekey'), 'recaptcha_sitekey') }}

        {{ html()->text('recaptcha_sitekey')->class([
            'form-control',
            'is-invalid' => $errors->has('recaptcha_sitekey')
        ]) }}

        @error('recaptcha_sitekey')
        {{ html()->span($message)->class('invalid-feedback') }}
        @enderror
    </div>

    <div class="form-group col-md-6">
        {{ html()->label($model::getAttributeLabel('recaptcha_secret'), 'recaptcha_secret') }}

        {{ html()->text('recaptcha_secret')->class([
            'form-control',
            'is-invalid' => $errors->has('recaptcha_secret')
        ]) }}

        @error('recaptcha_secret')
        {{ html()->span($message)->class('invalid-feedback') }}
        @enderror
    </div>
</div>

{{ html()->submit($submit['label'])->class('mt-3 btn btn-' . $submit['type']) }}

{{ html()->closeModelForm() }}
```

3. Create form request and define it in `config/admin.php` in `settings` section as `basic_request_class`.
You can create completely new request extending it from [AbstractBasicRequest](./src/Contracts/Setting/AbstractBasicRequest.php) class.
But you can simply extend it from existing [UpdateBasic](./src/Http/Requests/Setting/UpdateBasic.php) request.

```php
<?php
declare(strict_types=1);

namespace App\Http\Requests\Setting;

use SP\Admin\Http\Requests\Setting\UpdateBasic;

/**
 * Class MyUpdateBasic.
 *
 * @package App\Http\Requests\Setting
 */
final class MyUpdateBasic extends UpdateBasic
{
    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        $rules = [
            'recaptcha_sitekey' => 'max:255',
            'recaptcha_secret' => 'max:255',
        ];
        
        return \array_merge(parent::rules(), $rules);
    }

}
```

4. Create repository and define it in `config/admin.php` in `settings` section as `basic_repository_class`.
You can create completely new repository extending it from [AbstractBasicRepository](./src/Contracts/Setting/AbstractBasicRepository.php) class.
But you can simply extend it from existing [SettingBasicRepository](./src/Models/Repositories/SettingBasicRepository.php) repository.

```php
<?php
declare(strict_types=1);

namespace App\Repositories;

use SP\Admin\Contracts\Setting\AbstractBasic;
use SP\Admin\Models\Repositories\SettingBasicRepository;

/**
 * Class MySettingBasicRepository.
 *
 * @package App\Repositories
 */
final class MySettingBasicRepository extends SettingBasicRepository
{
    /**
     * {@inheritDoc}
     */
    public function modelDetailsConfig(AbstractBasic $model): array
    {
        $data = $model->getAll();

        $details = [
            [
                'label' => $model::getAttributeLabel('recaptcha_sitekey'),
                'value' => $data['recaptcha_sitekey'] ?? '-',
            ],
            [
            [
                'label' => $model::getAttributeLabel('recaptcha_secret'),
                'value' => $data['recaptcha_secret'] ?? '-',
            ],
        ];
        
        return \array_merge(parent::modelDetailsConfig($model), $details);
    }
    
    /**
     * Init script for Google Recaptcha.
     *
     * @return string
     */
    public function recaptchaApiScript(): string
    {
        $lang = config('app.locale');
        
        return <<<JS
<script
    src="https://www.google.com/recaptcha/api.js?onload=recaptchaCallback&render=explicit&hl=$lang"
    async defer></script>
JS;
    }

}
```

That is it!

Analytics services
------------------

To use analytics for your site add service identifier(s).
Then register scripts by adding `analytics()` function in
main front layout.

```
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>Laravel</title>
    
    {!! analytics() !!}
</head>
```

Registration automatically will be disabled
if `debug=true` or/and identifier(s) not set.

Additional user scripts
-----------------------

Site owner can add scripts here without developer\'s help.
You just need to register js files in `head` and `body` sections of the page
in main front layout.

```
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>Laravel</title>
    
    {!! head_scripts() !!}
</head>
<body>

    {!! bottom_scripts() !!}
</body>
```

Scheduled tasks
---------------

Administrator can create cron tasks from UI.
Just add registerer to `app/Console/Kernel.php`.

```php
// app/Console/Kernel.php

/**
 * Define the application's command schedule.
 *
 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
 */
protected function schedule(Schedule $schedule): void
{
    // $schedule->command('inspire')
    //          ->hourly();

    ScheduledTaskRepository::registerTasks($schedule);
}
```

---

[Table of contents](./index.md)
