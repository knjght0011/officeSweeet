<div id="StockModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Stock Report</h4>
      </div>
      <div class="modal-body">
          <table class="table" id="StockTable">
              <thead>
                  <tr>
                      <td>SKU</td>
                      <td>Description</td>
                      <td>Stock</td>
                      <td>Ordered</td>
                      <td>Status</td>
                      <td></td>
                  </tr>
              </thead>
              <tbody>
                  <tr class="stockrow">
                        <td>
                            Test
                        </td>
                        <td>
                            Test
                        </td>
                        <td>
                            1
                        </td>
                        <td>
                            2
                        </td>
                        <td>
                            Test
                        </td>
                        <td>
                            Test
                        </td>
                  </tr>
              </tbody>
          </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Save & Close</button>
      </div>
    </div>

  </div>
</div>

<script>
$(document).ready(function() {
    $('#StockModal').on('shown.bs.modal', function () {
        $("#StockTable").find('.stockrow').remove();
        $data = $(this).data('data');
        $.each($data, function( key, value ){

            if(value != undefined){
                var $row = value.split('/');
                $("#StockTable tbody").append('<tr class="stockrow"><td>' + $row[0] + '</td><td>' + $row[1] + '</td><td>' + $row[2] + '</td><td>' + $row[3] + '</td><td>' + $row[4] + '</td><td></td></tr>');
            }
        });
    });
});
</script>