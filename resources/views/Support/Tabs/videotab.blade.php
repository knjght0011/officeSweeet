<div class="col-md-3">
    <div class="panel-group" id="accordion">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseClient">All Videos</a>
                </h4>
            </div>
            <div id="collapseClient" class="panel-collapse collapse in">
                <div class="panel-body">
                    <div class="list-group">
                        @foreach(\App\Models\Management\SupportVideos::all() as $video)
                            <a href="#" class="list-group-item youtubebutton" data-url="{{ $video->link }}">{{ $video->name }}</a>
                        @endforeach
                        {{--
                        <a href="#" class="list-group-item youtubebutton" data-url="JVyTc57DRY8">Introduction to OfficeSweeet</a>
                        <a href="#" class="list-group-item youtubebutton" data-url="6mEqFkiK8MI">Getting Started with OfficeSweeet</a>
                        <a href="#" class="list-group-item youtubebutton" data-url="qGNZ4-3y5Ts">Getting Started with the Journal</a>
                        <a href="#" class="list-group-item youtubebutton" data-url="bu4weO27LRk">Action Menu Creating Documents, Invoices, Notes and more</a>
                        <a href="#" class="list-group-item youtubebutton" data-url="x9VenfL9m8Y">Checkbook Balancing and Journal Maintenance</a>
                        <a href="#" class="list-group-item youtubebutton" data-url="IIMw-FqYNSo">Clocking, Timesheets and Payroll</a>
                        <a href="#" class="list-group-item youtubebutton" data-url="25xmEUdnLB4">Scheduling Tools</a>
                        <a href="#" class="list-group-item youtubebutton" data-url="SKFHptkvBtU">Task Manager</a>
                        <a href="#" class="list-group-item youtubebutton" data-url="a5SLJaFAUFQ">Creating OfficeSweeet Templates</a>
                        <a href="#" class="list-group-item youtubebutton" data-url="eNS0HH4m7iU">Organizing Receipts</a>
                        --}}
                    <!--<a href="#" class="list-group-item youtubebutton" data-url=""></a>-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@desktop
<div style="height: calc(100% - 42px);" class="col-md-9">
    <iframe style="width: 100%; height: 100%;"id="ShowYoutubeFrame" src="{{ url('videoselect.html') }}"></iframe>
</div>
@enddesktop