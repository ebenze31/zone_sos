<div class="form-group {{ $errors->has('name_zone_area') ? 'has-error' : ''}}">
    <label for="name_zone_area" class="control-label">{{ 'Name Zone Area' }}</label>
    <input class="form-control" name="name_zone_area" type="text" id="name_zone_area" value="{{ isset($zone_area->name_zone_area) ? $zone_area->name_zone_area : ''}}" >
    {!! $errors->first('name_zone_area', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('polygon_area') ? 'has-error' : ''}}">
    <label for="polygon_area" class="control-label">{{ 'Polygon Area' }}</label>
    <input class="form-control" name="polygon_area" type="text" id="polygon_area" value="{{ isset($zone_area->polygon_area) ? $zone_area->polygon_area : ''}}" >
    {!! $errors->first('polygon_area', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('zone_partner_id') ? 'has-error' : ''}}">
    <label for="zone_partner_id" class="control-label">{{ 'Zone Partner Id' }}</label>
    <input class="form-control" name="zone_partner_id" type="text" id="zone_partner_id" value="{{ isset($zone_area->zone_partner_id) ? $zone_area->zone_partner_id : ''}}" >
    {!! $errors->first('zone_partner_id', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('creator') ? 'has-error' : ''}}">
    <label for="creator" class="control-label">{{ 'Creator' }}</label>
    <input class="form-control" name="creator" type="text" id="creator" value="{{ isset($zone_area->creator) ? $zone_area->creator : ''}}" >
    {!! $errors->first('creator', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('check_send_to') ? 'has-error' : ''}}">
    <label for="check_send_to" class="control-label">{{ 'Check Send To' }}</label>
    <input class="form-control" name="check_send_to" type="text" id="check_send_to" value="{{ isset($zone_area->check_send_to) ? $zone_area->check_send_to : ''}}" >
    {!! $errors->first('check_send_to', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('group_line_id') ? 'has-error' : ''}}">
    <label for="group_line_id" class="control-label">{{ 'Group Line Id' }}</label>
    <input class="form-control" name="group_line_id" type="text" id="group_line_id" value="{{ isset($zone_area->group_line_id) ? $zone_area->group_line_id : ''}}" >
    {!! $errors->first('group_line_id', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('last_edit_polygon_user_id') ? 'has-error' : ''}}">
    <label for="last_edit_polygon_user_id" class="control-label">{{ 'Last Edit Polygon User Id' }}</label>
    <input class="form-control" name="last_edit_polygon_user_id" type="text" id="last_edit_polygon_user_id" value="{{ isset($zone_area->last_edit_polygon_user_id) ? $zone_area->last_edit_polygon_user_id : ''}}" >
    {!! $errors->first('last_edit_polygon_user_id', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('last_edit_polygon_time') ? 'has-error' : ''}}">
    <label for="last_edit_polygon_time" class="control-label">{{ 'Last Edit Polygon Time' }}</label>
    <input class="form-control" name="last_edit_polygon_time" type="text" id="last_edit_polygon_time" value="{{ isset($zone_area->last_edit_polygon_time) ? $zone_area->last_edit_polygon_time : ''}}" >
    {!! $errors->first('last_edit_polygon_time', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('old_polygon_area') ? 'has-error' : ''}}">
    <label for="old_polygon_area" class="control-label">{{ 'Old Polygon Area' }}</label>
    <input class="form-control" name="old_polygon_area" type="text" id="old_polygon_area" value="{{ isset($zone_area->old_polygon_area) ? $zone_area->old_polygon_area : ''}}" >
    {!! $errors->first('old_polygon_area', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('old_edit_polygon_user_id') ? 'has-error' : ''}}">
    <label for="old_edit_polygon_user_id" class="control-label">{{ 'Old Edit Polygon User Id' }}</label>
    <input class="form-control" name="old_edit_polygon_user_id" type="text" id="old_edit_polygon_user_id" value="{{ isset($zone_area->old_edit_polygon_user_id) ? $zone_area->old_edit_polygon_user_id : ''}}" >
    {!! $errors->first('old_edit_polygon_user_id', '<p class="help-block">:message</p>') !!}
</div>


<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? 'Update' : 'Create' }}">
</div>
