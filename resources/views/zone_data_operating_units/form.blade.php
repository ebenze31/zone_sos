<div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
    <label for="name" class="control-label">{{ 'Name' }}</label>
    <input class="form-control" name="name" type="text" id="name" value="{{ isset($zone_data_operating_unit->name) ? $zone_data_operating_unit->name : ''}}" >
    {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('zone_area') ? 'has-error' : ''}}">
    <label for="zone_area" class="control-label">{{ 'Zone Area' }}</label>
    <input class="form-control" name="zone_area" type="text" id="zone_area" value="{{ isset($zone_data_operating_unit->zone_area) ? $zone_data_operating_unit->zone_area : ''}}" >
    {!! $errors->first('zone_area', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('level') ? 'has-error' : ''}}">
    <label for="level" class="control-label">{{ 'Level' }}</label>
    <input class="form-control" name="level" type="text" id="level" value="{{ isset($zone_data_operating_unit->level) ? $zone_data_operating_unit->level : ''}}" >
    {!! $errors->first('level', '<p class="help-block">:message</p>') !!}
</div>


<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? 'Update' : 'Create' }}">
</div>
