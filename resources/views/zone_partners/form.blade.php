<div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
    <label for="name" class="control-label">{{ 'Name' }}</label>
    <input class="form-control" name="name" type="text" id="name" value="{{ isset($zone_partner->name) ? $zone_partner->name : ''}}" >
    {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('full_name') ? 'has-error' : ''}}">
    <label for="full_name" class="control-label">{{ 'Full Name' }}</label>
    <input class="form-control" name="full_name" type="text" id="full_name" value="{{ isset($zone_partner->full_name) ? $zone_partner->full_name : ''}}" >
    {!! $errors->first('full_name', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('phone') ? 'has-error' : ''}}">
    <label for="phone" class="control-label">{{ 'Phone' }}</label>
    <input class="form-control" name="phone" type="text" id="phone" value="{{ isset($zone_partner->phone) ? $zone_partner->phone : ''}}" >
    {!! $errors->first('phone', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('type_partner') ? 'has-error' : ''}}">
    <label for="type_partner" class="control-label">{{ 'Type Partner' }}</label>
    <input class="form-control" name="type_partner" type="text" id="type_partner" value="{{ isset($zone_partner->type_partner) ? $zone_partner->type_partner : ''}}" >
    {!! $errors->first('type_partner', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('group_line_id') ? 'has-error' : ''}}">
    <label for="group_line_id" class="control-label">{{ 'Group Line Id' }}</label>
    <input class="form-control" name="group_line_id" type="text" id="group_line_id" value="{{ isset($zone_partner->group_line_id) ? $zone_partner->group_line_id : ''}}" >
    {!! $errors->first('group_line_id', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('mail') ? 'has-error' : ''}}">
    <label for="mail" class="control-label">{{ 'Mail' }}</label>
    <input class="form-control" name="mail" type="text" id="mail" value="{{ isset($zone_partner->mail) ? $zone_partner->mail : ''}}" >
    {!! $errors->first('mail', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('logo') ? 'has-error' : ''}}">
    <label for="logo" class="control-label">{{ 'Logo' }}</label>
    <input class="form-control" name="logo" type="text" id="logo" value="{{ isset($zone_partner->logo) ? $zone_partner->logo : ''}}" >
    {!! $errors->first('logo', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('color_ci_1') ? 'has-error' : ''}}">
    <label for="color_ci_1" class="control-label">{{ 'Color Ci 1' }}</label>
    <input class="form-control" name="color_ci_1" type="text" id="color_ci_1" value="{{ isset($zone_partner->color_ci_1) ? $zone_partner->color_ci_1 : ''}}" >
    {!! $errors->first('color_ci_1', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('color_ci_2') ? 'has-error' : ''}}">
    <label for="color_ci_2" class="control-label">{{ 'Color Ci 2' }}</label>
    <input class="form-control" name="color_ci_2" type="text" id="color_ci_2" value="{{ isset($zone_partner->color_ci_2) ? $zone_partner->color_ci_2 : ''}}" >
    {!! $errors->first('color_ci_2', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('color_ci_3') ? 'has-error' : ''}}">
    <label for="color_ci_3" class="control-label">{{ 'Color Ci 3' }}</label>
    <input class="form-control" name="color_ci_3" type="text" id="color_ci_3" value="{{ isset($zone_partner->color_ci_3) ? $zone_partner->color_ci_3 : ''}}" >
    {!! $errors->first('color_ci_3', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('province') ? 'has-error' : ''}}">
    <label for="province" class="control-label">{{ 'Province' }}</label>
    <input class="form-control" name="province" type="text" id="province" value="{{ isset($zone_partner->province) ? $zone_partner->province : ''}}" >
    {!! $errors->first('province', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('district') ? 'has-error' : ''}}">
    <label for="district" class="control-label">{{ 'District' }}</label>
    <input class="form-control" name="district" type="text" id="district" value="{{ isset($zone_partner->district) ? $zone_partner->district : ''}}" >
    {!! $errors->first('district', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('sub_district') ? 'has-error' : ''}}">
    <label for="sub_district" class="control-label">{{ 'Sub District' }}</label>
    <input class="form-control" name="sub_district" type="text" id="sub_district" value="{{ isset($zone_partner->sub_district) ? $zone_partner->sub_district : ''}}" >
    {!! $errors->first('sub_district', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('sub_area') ? 'has-error' : ''}}">
    <label for="sub_area" class="control-label">{{ 'Sub Area' }}</label>
    <input class="form-control" name="sub_area" type="text" id="sub_area" value="{{ isset($zone_partner->sub_area) ? $zone_partner->sub_area : ''}}" >
    {!! $errors->first('sub_area', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('show_homepage') ? 'has-error' : ''}}">
    <label for="show_homepage" class="control-label">{{ 'Show Homepage' }}</label>
    <input class="form-control" name="show_homepage" type="text" id="show_homepage" value="{{ isset($zone_partner->show_homepage) ? $zone_partner->show_homepage : ''}}" >
    {!! $errors->first('show_homepage', '<p class="help-block">:message</p>') !!}
</div>


<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? 'Update' : 'Create' }}">
</div>
