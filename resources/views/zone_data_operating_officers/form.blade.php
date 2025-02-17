<div class="form-group {{ $errors->has('name_officer') ? 'has-error' : ''}}">
    <label for="name_officer" class="control-label">{{ 'Name Officer' }}</label>
    <input class="form-control" name="name_officer" type="text" id="name_officer" value="{{ isset($zone_data_operating_officer->name_officer) ? $zone_data_operating_officer->name_officer : ''}}" >
    {!! $errors->first('name_officer', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('lat') ? 'has-error' : ''}}">
    <label for="lat" class="control-label">{{ 'Lat' }}</label>
    <input class="form-control" name="lat" type="text" id="lat" value="{{ isset($zone_data_operating_officer->lat) ? $zone_data_operating_officer->lat : ''}}" >
    {!! $errors->first('lat', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('lng') ? 'has-error' : ''}}">
    <label for="lng" class="control-label">{{ 'Lng' }}</label>
    <input class="form-control" name="lng" type="text" id="lng" value="{{ isset($zone_data_operating_officer->lng) ? $zone_data_operating_officer->lng : ''}}" >
    {!! $errors->first('lng', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('zone_operating_unit_id') ? 'has-error' : ''}}">
    <label for="zone_operating_unit_id" class="control-label">{{ 'Zone Operating Unit Id' }}</label>
    <input class="form-control" name="zone_operating_unit_id" type="text" id="zone_operating_unit_id" value="{{ isset($zone_data_operating_officer->zone_operating_unit_id) ? $zone_data_operating_officer->zone_operating_unit_id : ''}}" >
    {!! $errors->first('zone_operating_unit_id', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('user_id') ? 'has-error' : ''}}">
    <label for="user_id" class="control-label">{{ 'User Id' }}</label>
    <input class="form-control" name="user_id" type="text" id="user_id" value="{{ isset($zone_data_operating_officer->user_id) ? $zone_data_operating_officer->user_id : ''}}" >
    {!! $errors->first('user_id', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}">
    <label for="status" class="control-label">{{ 'Status' }}</label>
    <input class="form-control" name="status" type="text" id="status" value="{{ isset($zone_data_operating_officer->status) ? $zone_data_operating_officer->status : ''}}" >
    {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
</div>


<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? 'Update' : 'Create' }}">
</div>
