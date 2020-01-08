{{ html()->modelForm($model, $method, $route)->open() }}


<div class="form-group">
    {{ html()->label($model::getAttributeLabel($model::HEAD), $model::HEAD) }}

    {{ html()->textarea($model::HEAD)->class([
        'form-control',
        'is-invalid' => $errors->has($model::HEAD)
    ])->placeholder('// code here') }}

    @error($model::HEAD)
    {{ html()->span($message)->class('invalid-feedback') }}
    @enderror
</div>


<div class="form-group">
    {{ html()->label($model::getAttributeLabel($model::BOTTOM), $model::BOTTOM) }}

    {{ html()->textarea($model::BOTTOM)->class([
        'form-control',
        'is-invalid' => $errors->has($model::BOTTOM)
    ])->placeholder('// code here') }}

    @error($model::BOTTOM)
    {{ html()->span($message)->class('invalid-feedback') }}
    @enderror
</div>


{{ html()->submit($submit['label'])->class('mt-3 btn btn-' . $submit['type']) }}


{{ html()->closeModelForm() }}
