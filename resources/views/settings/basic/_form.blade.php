{{ html()->modelForm($model->getAll(), $method, $route)->attribute('enctype', 'multipart/form-data')->open() }}


<div class="form-row">
    <div class="form-group col-md-4">
        {{ html()->label($model::getAttributeLabel('admin_lang'), 'admin_lang') }}

        {{ html()->select('admin_lang', config('admin.languages'))->class([
            'custom-select',
            'is-invalid' => $errors->has('admin_lang'),
        ]) }}

        @error('admin_lang')
        {{ html()->span($message)->class('invalid-feedback') }}
        @enderror
    </div>
</div>


<div class="form-group">
    {{ html()->label($model::getAttributeLabel('app_name'), 'app_name') }}

    {{ html()->text('app_name')->class([
        'form-control',
        'is-invalid' => $errors->has('app_name')
    ]) }}

    @error('app_name')
    {{ html()->span($message)->class('invalid-feedback') }}
    @enderror
</div>


@if($model['app_logo'])
    <img class="img-thumbnail" src="{{ $app_logo_url ?? '' }}" alt="">
@endif

<div class="custom-file mt-3 mb-4">
    {{ html()->file('app_logo')->class([
        'custom-file-input',
        'is-invalid' => $errors->has('app_logo'),
    ]) }}

    {{ html()->label($model::getAttributeLabel('app_logo'), 'app_logo')
        ->class('custom-file-label')
        ->data('browse', trans('Choose file')) }}

    @error('app_logo')
    {{ html()->span($message)->class('invalid-feedback') }}
    @enderror
</div>


<div class="form-row">
    <div class="form-group col-md-4">
        {{ html()->label($model::getAttributeLabel('timezone'), 'timezone') }}

        {{ html()->select('timezone', $tz_list)->class([
            'custom-select js-select2',
            'is-invalid' => $errors->has('timezone'),
        ]) }}

        @error('timezone')
        {{ html()->span($message)->class('invalid-feedback') }}
        @enderror
    </div>
</div>


<br>
<h4>
    {{ __('Email smtp gate for sending messages') }}
    <small>{{ __('Technical Mail') }}</small>
</h4>

<div class="form-group">
    {{ html()->label($model::getAttributeLabel('mail_gate_host'), 'mail_gate_host') }}

    {{ html()->text('mail_gate_host')->class([
        'form-control',
        'is-invalid' => $errors->has('mail_gate_host')
    ]) }}

    @error('mail_gate_host')
    {{ html()->span($message)->class('invalid-feedback') }}
    @enderror
</div>

<div class="form-row">
    <div class="form-group col-md-6">
        {{ html()->label($model::getAttributeLabel('mail_gate_login'), 'mail_gate_login') }}

        {{ html()->text('mail_gate_login')->class([
            'form-control',
            'is-invalid' => $errors->has('mail_gate_login')
        ]) }}

        @error('mail_gate_login')
        {{ html()->span($message)->class('invalid-feedback') }}
        @enderror
    </div>

    <div class="form-group col-md-6">
        {{ html()->label($model::getAttributeLabel('mail_gate_password'), 'mail_gate_password') }}

        {{ html()->password('mail_gate_password')->class([
            'form-control',
            'is-invalid' => $errors->has('mail_gate_password')
        ]) }}

        @error('mail_gate_password')
        {{ html()->span($message)->class('invalid-feedback') }}
        @enderror
    </div>
</div>

<div class="form-row">
    <div class="form-group col-md-6">
        {{ html()->label($model::getAttributeLabel('mail_gate_port'), 'mail_gate_port') }}

        {{ html()->text('mail_gate_port')->class([
            'form-control',
            'is-invalid' => $errors->has('mail_gate_port')
        ]) }}

        @error('mail_gate_port')
        {{ html()->span($message)->class('invalid-feedback') }}
        @enderror
    </div>

    <div class="form-group col-md-6">
        {{ html()->label($model::getAttributeLabel('mail_gate_encryption'), 'mail_gate_encryption') }}

        {{ html()->select('mail_gate_encryption', $mail_encrypt_list)->class([
            'custom-select',
            'is-invalid' => $errors->has('mail_gate_encryption')
        ]) }}

        @error('mail_gate_encryption')
        {{ html()->span($message)->class('invalid-feedback') }}
        @enderror
    </div>
</div>


{{ html()->submit($submit['label'])->class('mt-3 btn btn-' . $submit['type']) }}


{{ html()->closeModelForm() }}
