@if(isset($llsclientinfo))
@if(count($llsclientinfo) === 1)
    <div class="row" style="margin-top: 10px;">

            <button style="margin-left: 10px;" id="OStab-resend-welcome" name="save" type="button" class="btn OS-Button col-md-2">Resend Welcome E-Mail</button>

    </div>
    <div class="row">
        <table class="table">
            <tr>
                <td>
                    Plan Name
                </td>
                <td>
                    {{ $llsclientinfo->plan_name }}
                </td>
                <td>
                    Account Info
                </td>
                <td>
                    <a href="https://lls.officesweeet.com/Accounts/{{ $llsclientinfo->subdomain }}">here</a>
                </td>
                <td>
                    Link to system
                </td>
                <td>
                    <a href="https://{{ $llsclientinfo->subdomain }}.officesweeet.com" target="_blank" >{{ $llsclientinfo->subdomain }}</a>
                </td>
            </tr>
            <tr>
                <td>
                    Licensed Users
                </td>
                <td>
                    {{ $llsclientinfo->licensedusers }}
                    <button id="ostab-modify-user" class="btn OS-Button" type="button">Modify</button>
                </td>
                <td>
                    Current Number of Users(That can login)
                </td>
                <td>
                    {{ $usercount }}
                </td>
                <td>
                    Company Type
                </td>
                <td>
                    @if(isset($llsclientinfo->installinfo['companytype-setup']))
                    {{ $llsclientinfo->installinfo['companytype-setup'] }}
                    @endif
                </td>
            </tr>
            <tr>
                <td>
                    Active Till Date:
                </td>
                <td>
                    {{ $llsclientinfo->active }} <button id="ostab-modify-active" class="btn OS-Button" type="button">Modify</button>
                </td>
                <td>

                </td>
                <td>

                </td>
                <td>

                </td>
                <td>

                </td>
            </tr>
        </table>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Last Login</th>
            </tr>
        </thead>
        <tbody>
        @foreach($clientusers as $user)
            <tr>
                <td>{{ $user->firstname }} {{ $user->lastname }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->last_login  }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <script>
        $('#ostab-modify-user').click(function () {
            $.confirm({
                title: 'WARNING! USE WITH CARE!!!',
                content: '' +
                '<form action="" class="formName">' +
                '<div class="form-group">' +
                '<label>New Number Of User</label>' +
                '<input id="ostab-modify-user-input" type="text" class="name form-control" required/>' +
                '</div>' +
                '</form>',
                buttons: {
                    formSubmit: {
                        text: 'Submit',
                        btnClass: 'btn-blue',
                        action: function () {
                            $("body").addClass("loading");

                            $data = {};
                            $data['_token'] = "{{ csrf_token() }}";
                            $data['accountid'] = "{{ $llsclientinfo->id }}";
                            $data['users'] = $('#ostab-modify-user-input').val();

                            $post = $.post("/Account/SetUsers", $data);

                            $post.done(function (data) {
                                $("body").removeClass("loading");
                                switch (data['status']) {
                                    case "OK":
                                        SavedSuccess();
                                        window.location.reload();
                                        break;
                                    case "notfound":
                                        $.dialog({
                                            title: 'Oops...',
                                            content: 'Unknown Response from server. Please refresh the page and try again.'
                                        });
                                        break;
                                    default:
                                        console.log(data);
                                        $.dialog({
                                            title: 'Oops...',
                                            content: 'Unknown Response from server. Please refresh the page and try again.'
                                        });
                                }
                            });

                            $post.fail(function () {
                                NoReplyFromServer();
                            });

                        },
                    },
                    cancel: function () {
                        //close
                    },
                },
            });
        });

        $('#ostab-modify-active').click(function () {
            $.confirm({
                onContentReady: function () {

                    $('#ostab-modify-active-input').datepicker({
                        changeMonth: true,
                        changeYear: true,
                        controlType: 'select',
                        parse: "loose",
                        dateFormat: "yy-mm-dd",
                    });
                },
                onClose: function(){
                    $("#ostab-modify-active-input").datepicker("destroy");
                },
                title: 'WARNING! USE WITH CARE!!!',
                content: '' +
                '<form action="" class="formName">' +
                '<div class="form-group">' +
                '<label>New Active Till Date</label>' +
                '<input id="ostab-modify-active-input" type="text" class="name form-control" required readonly/>' +
                '</div>' +
                '</form>',
                buttons: {
                    formSubmit: {
                        text: 'Submit',
                        btnClass: 'btn-blue',
                        action: function () {
                            $("body").addClass("loading");



                            $data = {};
                            $data['_token'] = "{{ csrf_token() }}";
                            $data['accountid'] = "{{ $llsclientinfo->id }}";
                            $data['active'] = $('#ostab-modify-active-input').val();

                            $post = $.post("/Account/SetActiveDate", $data);

                            $post.done(function (data) {
                                $("body").removeClass("loading");
                                switch (data['status']) {
                                    case "OK":
                                        SavedSuccess();
                                        window.location.reload();
                                        break;
                                    case "notfound":
                                        $.dialog({
                                            title: 'Oops...',
                                            content: 'Unknown Response from server. Please refresh the page and try again.'
                                        });
                                        break;
                                    default:
                                        console.log(data);
                                        $.dialog({
                                            title: 'Oops...',
                                            content: 'Unknown Response from server. Please refresh the page and try again.'
                                        });
                                }
                            });

                            $post.fail(function () {
                                NoReplyFromServer();
                            });

                        },
                    },
                    cancel: function () {
                        //close
                    },
                },
            });
        });

        $("#OStab-resend-welcome").click(function()
        {
            $.confirm({
                title: 'Welcome Mail',
                content: '' +
                '<div class="form-group">' +
                '<label>email address:</label>' +
                '<input  type="text" class="form-control" required />' +
                '</div>',
                buttons: {
                    formSubmit: {
                        text: 'Submit',
                        btnClass: 'btn-blue',
                        action: function () {
                            $("body").addClass("loading");
                            var name = this.$content.find('.name').val();
                            var get = $.get( "/Clients/ResendEmail/{{ $llsclientinfo->id }}/" + name , function(  ) { });

                            get.done(function( data ) {
                                $("body").removeClass("loading");
                                $.dialog({
                                    title: 'Success!!',
                                    content: 'Email Sent.',
                                });
                            });
                        }
                    },
                    cancel: function () {
                        //close
                    },
                },
                onContentReady: function () {
                    // bind to events
                    var jc = this;
                    this.$content.find('form').on('submit', function (e) {
                        // if the user submits the form by pressing enter in the field.
                        e.preventDefault();
                        jc.$$formSubmit.trigger('click'); // reference the button and click it
                    });
                }
            });


        });
    </script>
@else
No Office Sweeet Record found

<a href="/LLSNEWSignup/{{ $client->id }}">SIGNUP NOW</a>
@endif
@else
    No Office Sweeet Record found

    <a href="/LLSNEWSignup/{{ $client->id }}">SIGNUP NOW</a>
@endif