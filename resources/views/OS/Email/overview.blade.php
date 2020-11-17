
    <table class="table">
        <thead>
            <tr>
                <th>Type</th>
                <th>Recipient</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Type</th>
                <th>Recipient</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </tfoot>
        <tbody>
            @foreach($emails as $email)
            <tr>
                <td>{{ $email->type }}</td>
                <td>{{ $email->email }}</td>
                <td>{{ $email->Status() }}</td>
                <td>{{ $email->created_at }}</td>
            </tr>
            @endforeach
        </tbody>

    </table>


    <script>
        $(document).ready(function() {


        });
    </script>
