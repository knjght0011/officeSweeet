    <div class="row" style="margin-top: 50px;">
        <div class="col-md-2">

        </div>

        <div class="col-md-4">
            <table class="table">
                @foreach($summeryarray as $key => $value)
                <tr>
                    <td>
                        {{ $key }}
                    </td>
                    <td>
                        {{ $value }}
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
