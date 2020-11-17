<style>
.element {
    margin-bottom: 10px;
}
.element input:read-only {
    background-color: white;
}
.element textarea:read-only {
    background-color: white;
}
</style>

<br>
<div class="row">
    <div class="col-md-4">
        <div class="input-group">
            <label class="input-group-addon" for="edit-tabselect"><div style="width: 10em;">Tab Name:</div></label>
            <select id="edit-tabselect" name="selectbasic" class="form-control tabselect">
                @foreach($tables as $table)
                    <option value="{{ $table->id }}">{{ $table->displayname }}</option>
                @endforeach
            </select>
            <span style="height: 100%;" class="input-group-btn">
                <button type="button" class="btn btn-default" data-toggle="modal" data-target="#AddNewTabModal">Add New Tab</button>
            </span>
        </div>
    </div>

    <div class="col-md-4">
        <button style="width: 100%;" id="tab-basic-save" name="" class="btn OS-Button"><b>Save This Tab Before Leaving</b></button>
    </div>

    <div class="col-md-2">
        <div class="input-group">
            <label class="input-group-addon" for="type-display"><div>Type:</div></label>
            <input id="type-display" name="type-display" type="text" class="form-control" readonly>
        </div>
    </div>

    <div class="col-md-2">
        <!--<label class="col-md-4 control-label tablabel" for="inactive" style="padding-top: 10px">Inactive:</label>
        <div class="col-md-5 " style="padding-top: 10px">-->
            <input id="tab-enable-toggle" name="tab-enable-toggle" type="checkbox" data-on="Active" data-off="Inactive" data-toggle="toggle" data-width="100%">
        <!--</div>-->
    </div>

</div>
<br>

<button type="button" class="btn OS-Button" data-toggle="modal" data-target="#AddInputModal">Add Text Input Box</button>
<button type="button" class="btn OS-Button" data-toggle="modal" data-target="#AddDateInputModal">Add Date Input Box</button>
<button type="button" class="btn OS-Button" data-toggle="modal" data-target="#AddSelectModal">Add Dropdown/List Box</button>
<button type="button" class="btn OS-Button" data-toggle="modal" data-target="#AddTextAreaModal">Add Text Area Box</button><br><br>



<ul id="tabnavmaster" class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active" style="width: 50em;"><a id="tab-new" href="#general" aria-controls="home" role="tab" data-toggle="tab">
        <div class="input-group">
            <input id="tab-name" name="tab-name" type="text" class="form-control" readonly>
            <span style="height: 100%;" class="input-group-btn">
                <button id="tab-rename-enable" type="button" class="btn btn-default" data-toggle="modal" >Rename Tab</button>
            </span>
        </div>
    </a></li>
</ul>
<div class="tab-content" style="height: calc(100% - 42px); background-color: white;">
    <div role="tabpanel" class="tab-pane active" id="general" style="height: 100%;"> 
        <div id="design">
            <div id="col1" class="col-md-4">
 
            </div>
            <div id="col2" class="col-md-4">
                
            </div>
            <div id="col3" class="col-md-4">
                
            </div>            
        </div>
    </div>
</div>    




@include('ACP.Tabs.CustomTabs.java.new')

@include('ACP.Tabs.CustomTabs.java.addnewtab')
@include('ACP.Tabs.CustomTabs.java.rightclickmenu')

@include('ACP.Tabs.CustomTabs.java.addinput')
@include('ACP.Tabs.CustomTabs.java.adddateinput')
@include('ACP.Tabs.CustomTabs.java.addselect')
@include('ACP.Tabs.CustomTabs.java.addtextarea')
