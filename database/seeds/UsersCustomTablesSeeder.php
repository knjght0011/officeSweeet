<?php

use Illuminate\Database\Seeder;
use App\Models\CustomTables;

class UsersCustomTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CustomTables::create(array(
        'name'    => 'orders',
        'displayname' => 'Orders',
        'content'  => 'Orders <form class="form-horizontal">
<fieldset>

<!-- Form Name -->
<legend>Form Name</legend>

<!-- Search input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="searchinput">Search Input</label>
  <div class="col-md-4">
    <input id="searchinput" name="searchinput" type="search" placeholder="placeholder" class="form-control input-md">
    <p class="help-block">help</p>
  </div>
</div>

<!-- Appended Input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="appendedtext">Appended Text</label>
  <div class="col-md-4">
    <div class="input-group">
      <input id="appendedtext" name="appendedtext" class="form-control" placeholder="placeholder" type="text">
      <span class="input-group-addon">append</span>
    </div>
    <p class="help-block">help</p>
  </div>
</div>
<!-- Prepended checkbox -->
<div class="form-group">
  <label class="col-md-4 control-label" for="prependedcheckbox">Prepended Checkbox</label>
  <div class="col-md-4">
    <div class="input-group">
      <span class="input-group-addon">     
          <input type="checkbox">     
      </span>
      <input id="prependedcheckbox" name="prependedcheckbox" class="form-control" type="text" placeholder="placeholder">
    </div>
    <p class="help-block">help</p>
  </div>
</div>

<!-- Password input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="passwordinput">Password Input</label>
  <div class="col-md-4">
    <input id="passwordinput" name="passwordinput" type="password" placeholder="placeholder" class="form-control input-md">
    <span class="help-block">help</span>
  </div>
</div>

</fieldset>
</form>
',
    ));
        
    CustomTables::create(array(
        'name'    => 'test',
        'displayname' => 'test',
        'content'  => 'test <form class="form-horizontal">
<fieldset>

<!-- Form Name -->
<legend>Form Name</legend>

<!-- Search input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="searchinput">Search Input</label>
  <div class="col-md-4">
    <input id="searchinput" name="searchinput" type="search" placeholder="placeholder" class="form-control input-md">
    <p class="help-block">help</p>
  </div>
</div>

<!-- Appended Input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="appendedtext">Appended Text</label>
  <div class="col-md-4">
    <div class="input-group">
      <input id="appendedtext" name="appendedtext" class="form-control" placeholder="placeholder" type="text">
      <span class="input-group-addon">append</span>
    </div>
    <p class="help-block">help</p>
  </div>
</div>
<!-- Prepended checkbox -->
<div class="form-group">
  <label class="col-md-4 control-label" for="prependedcheckbox">Prepended Checkbox</label>
  <div class="col-md-4">
    <div class="input-group">
      <span class="input-group-addon">     
          <input type="checkbox">     
      </span>
      <input id="prependedcheckbox" name="prependedcheckbox" class="form-control" type="text" placeholder="placeholder">
    </div>
    <p class="help-block">help</p>
  </div>
</div>

<!-- Password input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="passwordinput">Password Input</label>
  <div class="col-md-4">
    <input id="passwordinput" name="passwordinput" type="password" placeholder="placeholder" class="form-control input-md">
    <span class="help-block">help</span>
  </div>
</div>

</fieldset>
</form>
',
    ));
    
    CustomTables::create(array(
        'name'    => 'test2',
        'displayname' => 'test2',
        'content'  => 'test2 <form class="form-horizontal">
<fieldset>

<!-- Form Name -->
<legend>Form Name</legend>

<!-- Search input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="searchinput">Search Input</label>
  <div class="col-md-4">
    <input id="searchinput" name="searchinput" type="search" placeholder="placeholder" class="form-control input-md">
    <p class="help-block">help</p>
  </div>
</div>

<!-- Appended Input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="appendedtext">Appended Text</label>
  <div class="col-md-4">
    <div class="input-group">
      <input id="appendedtext" name="appendedtext" class="form-control" placeholder="placeholder" type="text">
      <span class="input-group-addon">append</span>
    </div>
    <p class="help-block">help</p>
  </div>
</div>
<!-- Prepended checkbox -->
<div class="form-group">
  <label class="col-md-4 control-label" for="prependedcheckbox">Prepended Checkbox</label>
  <div class="col-md-4">
    <div class="input-group">
      <span class="input-group-addon">     
          <input type="checkbox">     
      </span>
      <input id="prependedcheckbox" name="prependedcheckbox" class="form-control" type="text" placeholder="placeholder">
    </div>
    <p class="help-block">help</p>
  </div>
</div>

<!-- Password input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="passwordinput">Password Input</label>
  <div class="col-md-4">
    <input id="passwordinput" name="passwordinput" type="password" placeholder="placeholder" class="form-control input-md">
    <span class="help-block">help</span>
  </div>
</div>

</fieldset>
</form>
',
    ));
        
    }
}
