
    {{-- modal show detail --}}
    <div class="modal fade" id="view_detail"  ng-app="MyApp" ng-controller="AppCtrl as ctrl">
            <div class="modal-dialog modal-lg modal-dialog-centered">
              <div class="modal-content">
              
                <!-- Modal Header -->
                <div class="modal-header">
                  <h5 class="modal-title">Chi tiết sản phẩm
                    <span><u id="detail_product_name" class="text-danger"></u></span></h5>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                
                <!-- Modal body -->
                <div class="modal-body">
                  <table class="table  table-responsive-xl table-hover ">
                    <thead class="table-success">
                      <tr>
                        <th>STT</th>
                        <th>MSSV</th>
                        <th>Họ tên</th>
                      </tr>
                    </thead>
                    <tbody class=" table-bordered  table-striped" id="list_sv">
                    </tbody>
                  </table>
                </div>
                
                <!-- Modal footer -->
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                </div>
                
              </div>
            </div>
    </div>
     <script type="text/javascript">
      $('#view_detail').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget);
      var detail = button.data('detail');
      var modal = $(this);
      console.log(detail);
    //   modal.find('#tenmh_list').text(tenmh)
    //   modal.find('#tengv_list').text(giangvien)
    //   modal.find('#list_sv').html(table_sv)
    });
</script> 