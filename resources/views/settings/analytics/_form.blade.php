{{ html()->modelForm($model->getAll(), $method, $route)->open() }}


<div class="form-group">
    {{ html()->label($model::getAttributeLabel('google'), 'google') }}

    {{ html()->text('google')->class([
        'form-control',
        'is-invalid' => $errors->has('google')
    ]) }}

    @error('google')
    {{ html()->span($message)->class('invalid-feedback') }}
    @enderror
</div>


<div class="form-group">
    {{ html()->label($model::getAttributeLabel('yandex'), 'yandex') }}

    {{ html()->text('yandex')->class([
        'form-control',
        'is-invalid' => $errors->has('yandex')
    ]) }}

    @error('yandex')
    {{ html()->span($message)->class('invalid-feedback') }}
    @enderror
</div>


{{ html()->submit($submit['label'])->class('mt-3 btn btn-' . $submit['type']) }}


{{ html()->closeModelForm() }}
