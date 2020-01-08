{{ html()->modelForm($model, $method, $route)->open() }}


<div class="form-row">
    <div class="form-group col-md-6">
        {{ html()->label($model::getAttributeLabel('username'), 'username') }}

        {{ html()->text('username')->class([
            'form-control',
            'is-invalid' => $errors->has('username')
        ])->required() }}

        @error('username')
        {{ html()->span($message)->class('invalid-feedback') }}
        @enderror
    </div>


    <div class="form-group col-md-6">
        {{ html()->label($model::getAttributeLabel('password'), 'password_form') }}

        {{ html()->password('password_form')->class([
            'form-control',
            'is-invalid' => $errors->has('password_form')
        ])->required($password_required) }}

        @error('password_form')
        {{ html()->span($message)->class('invalid-feedback') }}
        @enderror
    </div>
</div>


<div class="form-row">
    <div class="form-group col-md-6">
        {{ html()->label($model::getAttributeLabel('email'), 'email') }}

        {{ html()->email('email')->class([
            'form-control',
            'is-invalid' => $errors->has('email')
        ])->required() }}

        @error('email')
        {{ html()->span($message)->class('invalid-feedback') }}
        @enderror
    </div>


    <div class="form-group col-md-6">
        {{ html()->label($model::getAttributeLabel('role'), 'role') }}

        @php
            /** @var \Illuminate\Contracts\Support\MessageBag $errors */
            $role_select = html()->select('role', \SP\Admin\Security\Role::getList())->class([
                'custom-select',
                'is-invalid' => $errors->has('role'),
            ]);

            if (auth('admin')->user()->cant(\SP\Admin\Security\Role::ADMIN)) {
                $role_select = $role_select->attribute('disabled', 'disabled');
            }
        @endphp

        {{ $role_select }}

        @error('role')
        {{ html()->span($message)->class('invalid-feedback') }}
        @enderror
    </div>
</div>


<div class="custom-control custom-checkbox">
    {{ html()->checkbox('active')->class([
        'custom-control-input',
        'is-invalid' => $errors->has('active')
    ]) }}

    {{ html()->label($model::getAttributeLabel('active'), 'active')->class('custom-control-label') }}

    @error('active')
    {{ html()->span($message)->class('invalid-feedback') }}
    @enderror
</div>


{{ html()->submit($submit['label'])->class('mt-3 btn btn-' . $submit['type']) }}


{{ html()->closeModelForm() }}
