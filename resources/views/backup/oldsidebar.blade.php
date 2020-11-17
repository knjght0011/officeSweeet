<div id="logo-background"><div id="logo-container"></div></div>
<div class="sidebar-nav" >
    <div class="navbar navbar-default" role="navigation">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <span class="visible-xs navbar-brand">Sidebar menu</span>
        </div>
        <div class="navbar-collapse collapse sidebar-navbar-collapse">
            <ul class="nav navbar-nav">

                <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown">{{ TextHelper::GetText("Clients") }} <span class="glyphicon glyphicon-chevron-right"></span></a>
                    <div class="dropup">
                        <ul class="dropdown-menu pull-right">
                            <li><a id="link" href="/Clients/Search/">Search</a></li>
                            <li><a id="link" href="/Clients/Add">Add</a></li> 
                        </ul>
                    </div>
                </li>

                <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown">Vendors <span class="glyphicon glyphicon-chevron-right"></span></a>
                    <div class="dropup">
                        <ul class="dropdown-menu pull-right">
                            <li><a id="link" href="/Vendors/Search/">Search</a></li>
                            <li><a id="link" href="/Vendors/Add">Add</a></li> 
                        </ul>
                    </div>
                </li>

                <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown">Employees <span class="glyphicon glyphicon-chevron-right"></span></a>
                    <div class="dropup">
                        <ul class="dropdown-menu pull-right">
                            <li><a id="link" href="/Employees/Search/">Search</a></li>
                            <li><a id="link" href="/Employees/Add">Add</a></li> 
                            <li><a id="link" href="/Employees/Payroll">Payroll</a></li>
                        </ul>
                    </div>
                </li>

                <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" >Templates <span class="glyphicon glyphicon-chevron-right"></span></a>
                    <div class="dropup">
                        <ul class="dropdown-menu pull-right">
                            <li><a id="link" href="/Templates/List">Edit Existing</a></li>
                            <li><a href="/Templates/New">Create From Scratch</a></li>
                            <li><a id="link" href="/Templates/Upload">Create From Document</a></li>
                        </ul>
                    </div>
                </li> 

                <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" >Reporting <span class="glyphicon glyphicon-chevron-right"></span></a>
                    <div class="dropup">
                        <ul class="dropdown-menu pull-right">
                            <li><a id="link" href="/Reporting/AR">Accounts Receivable</a></li>
                            <li><a id="link" href="/Reporting/Inventory">Inventory</a></li>
                            
                        </ul>
                    </div>
                </li>
                
                <li class="dropdown"><a class="dropdown-toggle" data-toggle="Outgoing" >Outgoing <span class="glyphicon glyphicon-chevron-right"></span></a>
                    <div class="dropup">
                        <ul class="dropdown-menu pull-right">
                            <li><a id="link" href="/Outgoing/Check">Write Check</a></li>
                            <li><a id="link" href="/Outgoing/payableinvoices">Payable Invoices</a></li>
                        </ul>
                    </div>
                </li>

                <li><a id="link" href="/Calendar/View">Calendar</a></li> 

                <!--
                <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="glyphicon glyphicon-chevron-right"></b></a>
                    <div class="dropup">
                        <ul class="dropdown-menu pull-right">
                            <li><a href="#">Action</a></li>
                            <li><a href="#">Another action</a></li>
                            <li><a href="#">Something else here</a></li>
                            <li class="divider"></li>
                            <li class="dropdown-header">Nav header</li>
                            <li><a href="#">Separated link</a></li>
                            <li><a href="#">One more separated link</a></li>
                        </ul>
                    </div>
                </li>
                -->
                
                <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" ><span class="glyphicon glyphicon-modal-window"></span> Admin  <b class="glyphicon glyphicon-chevron-right"></b></a>
                    <div class="dropup">
                        <ul class="dropdown-menu pull-right">
                            <li><a id="link" href="/Admin/Session">Session</a></li>
                            <li><a id="link" href="/Admin/User_Management">User Management</a></li>
                            <li><a id="link" href="/Admin/Products/Search">Products</a></li>
                            <li><a id="link" href="/Admin/Settings">Settings</a></li
                        </ul>
                    </div>
               </li>
               <li><a id="link" href="/account"><span class="glyphicon glyphicon-user"></span> {{ Auth::user()->email }} </a></li>
               <li><a href="/logout"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
           
           </ul>
        </div><!--/.nav-collapse -->
    </div>
</div>
