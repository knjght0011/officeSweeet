<script>
    function SavedSuccess($content = 'Data Saved', $title = "Success!", $timeout = "2000"){
        $.confirm({
            autoClose: 'Close|' + $timeout,
            title: $title,
            content: $content,
            buttons: {
                Close: function () {

                }
            }
        });
    }
    function NoReplyFromServer() {
        $("body").removeClass("loading");
        $.dialog({
            title: 'Oops...',
            content: 'Failed to contact server. Please try again later.'
        });
    }
    function ServerValidationErrors($array) {
        $text = "";
        $.each($array, function( index, value ) {
            $text = $text + value + "<br>";
        });
        $.dialog({
            title: 'Oops...',
            content: $text
        });
    }
</script>


</body>
</html>