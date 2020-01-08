{{ html()->modelForm($model, $method, $route)->open() }}


<div class="form-group">
    {{ html()->label($model::getAttributeLabel('name'), 'name') }}

    {{ html()->text('name')->class([
        'form-control',
        'is-invalid' => $errors->has('name')
    ])->required() }}

    @error('name')
    {{ html()->span($message)->class('invalid-feedback') }}
    @enderror
</div>


<div class="form-group">
    {{ html()->label($model::getAttributeLabel('min'), 'min') }}

    {{ html()->text('min')->class([
        'form-control',
        'is-invalid' => $errors->has('min')
    ])->placeholder('*')->required() }}

    @error('min')
    {{ html()->span($message)->class('invalid-feedback') }}
    @enderror
</div>


<div class="form-group">
    {{ html()->label($model::getAttributeLabel('hour'), 'hour') }}

    {{ html()->text('hour')->class([
        'form-control',
        'is-invalid' => $errors->has('hour')
    ])->placeholder('*')->required() }}

    @error('hour')
    {{ html()->span($message)->class('invalid-feedback') }}
    @enderror
</div>


<div class="form-group">
    {{ html()->label($model::getAttributeLabel('day'), 'day') }}

    {{ html()->text('day')->class([
        'form-control',
        'is-invalid' => $errors->has('day')
    ])->placeholder('*')->required() }}

    @error('day')
    {{ html()->span($message)->class('invalid-feedback') }}
    @enderror
</div>


<div class="form-group">
    {{ html()->label($model::getAttributeLabel('month'), 'month') }}

    {{ html()->text('month')->class([
        'form-control',
        'is-invalid' => $errors->has('month')
    ])->placeholder('*')->required() }}

    @error('month')
    {{ html()->span($message)->class('invalid-feedback') }}
    @enderror
</div>


<div class="form-group">
    {{ html()->label($model::getAttributeLabel('week_day'), 'week_day') }}

    {{ html()->text('week_day')->class([
        'form-control',
        'is-invalid' => $errors->has('week_day')
    ])->placeholder('*')->required() }}

    @error('week_day')
    {{ html()->span($message)->class('invalid-feedback') }}
    @enderror
</div>


<div class="form-group">
    {{ html()->label($model::getAttributeLabel('command'), 'command') }}

    {{ html()->text('command')->class([
        'form-control',
        'is-invalid' => $errors->has('command')
    ])->required() }}

    @error('command')
    {{ html()->span($message)->class('invalid-feedback') }}
    @enderror
</div>


<div class="form-row">
    <div class="form-group col-md-6">
        {{ html()->label($model::getAttributeLabel('out_file'), 'out_file') }}

        {{ html()->text('out_file')->class([
            'form-control',
            'is-invalid' => $errors->has('out_file')
        ])->placeholder('/tmp/out_file.txt') }}

        @error('out_file')
        {{ html()->span($message)->class('invalid-feedback') }}
        @enderror
    </div>

    <div class="form-group col-md-6">
        {{ html()->label($model::getAttributeLabel('file_write_method'), 'file_write_method') }}

        {{ html()->select('file_write_method', $method_list)->class([
            'custom-select',
            'is-invalid' => $errors->has('file_write_method'),
        ]) }}

        @error('file_write_method')
        {{ html()->span($message)->class('invalid-feedback') }}
        @enderror
    </div>
</div>


<div class="form-row">
    <div class="form-group col-md-6">
        {{ html()->label($model::getAttributeLabel('report_email'), 'report_email') }}

        {{ html()->email('report_email')->class([
            'form-control',
            'is-invalid' => $errors->has('report_email')
        ]) }}

        @error('report_email')
        {{ html()->span($message)->class('invalid-feedback') }}
        @enderror
    </div>

    <div class="col-md-6 pt-md-4">
        <div class="custom-control custom-checkbox">
            {{ html()->checkbox('report_only_error')->class([
                'custom-control-input',
                'is-invalid' => $errors->has('report_only_error')
            ]) }}

            {{ html()->label($model::getAttributeLabel('report_only_error'), 'report_only_error')->class('custom-control-label') }}

            @error('report_only_error')
            {{ html()->span($message)->class('invalid-feedback') }}
            @enderror
        </div>
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


<div class="d-flex justify-content-between mt-3">
    {{ html()->submit($submit['label'])->class('btn btn-' . $submit['type']) }}

    {{ html()->a('https://help.ubuntu.ru/wiki/cron', 'https://help.ubuntu.ru/wiki/cron')->attributes([
        'target' => '_blank'
    ]) }}
</div>


{{ html()->closeModelForm() }}
