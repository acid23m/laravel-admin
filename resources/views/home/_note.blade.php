@php $model = auth('admin')->user() @endphp

<h5>
    <i class="fa fa-file-alt mr-1"></i>
    {{ __('Note') }}
</h5>

<hr>

{{ html()->modelForm($model, 'put', route('admin.user-notes.update'))->open() }}

<div class="form-group">
    {{ html()->textarea('note')->class([
        'form-control',
        'is-invalid' => $errors->has('note')
    ]) }}

    @error('note')
    {{ html()->span($message)->class('invalid-feedback') }}
    @enderror
</div>

{{ html()->submit(trans('Save'))->class('btn btn-primary') }}

{{ html()->closeModelForm() }}
