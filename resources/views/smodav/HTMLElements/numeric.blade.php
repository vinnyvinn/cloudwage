<div class="form-md-line-input">
    <input type="number" class="form-control input-sm" id="{{ $name . $id }}" name="{{ $name . $id }}" value="{{ old('title') }}" placeholder="{{ title_case(str_replace('_', ' ', $name)) }}">
</div>