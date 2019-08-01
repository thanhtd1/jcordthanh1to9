
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="product-status-wrap">
                <h4>Products List</h4>
                <div class="add-product">
                    <a class="btn" ng-click="showForm($event,'add_product')">Thêm sản phẩm</a>
                </div>
                <table ng-init="load_product_list('')">
                    <tr>
                        <th>Hình</th>
                        <th>Tên Sp</th>
                        <th>Trạng thái</th>
                        <th>Số lượng còn</th>
                        <th>Ngày thêm</th>
                        <th>Trạng thái</th>
                        <th>Price (VNĐ)</th>
                        <th>Hành động</th>
                    </tr>
                    <tr ng-show="!product_list"><td colspan="8"><md-progress-linear md-mode="indeterminate"></md-progress-linear></td></tr>
                    <tr ng-model="product_list" ng-repeat="product in product_list.data" class="product-item">                                    
                        <td><img src="/<%product.image%>" alt="<%product.name%>" style="width:60px; height:60px"/></td>
                        <td><%product.name%></td>
                        <td>
                            <button class="pd-setting">Active</button>
                        </td>
                        <td><%product.quantity%></td>
                        <td><%product.created_at |date%></td>
                        <td>Out Of Stock</td>
                        <td><%product.price | number%></td>
                        <td>
                            <button data-toggle="tooltip" title="Xem chi tiết" class="pd-setting-ed" ng-click="ctrl.load_content('admin/view/product/'+product.id)"><i class="dripicons dripicons-preview" aria-hidden="true"></i></button>
                            <button data-toggle="tooltip" title="Chỉnh sửa" class="pd-setting-ed"><i class="dripicons dripicons-document-edit " aria-hidden="true"></i></button>
                            <button data-toggle="tooltip" title="Xoá" class="pd-setting-ed"><i class="dripicons dripicons-trash" aria-hidden="true"></i></button>
                        </td>
                    </tr>                                
                    <tr>
                        <td><button class="ps-setting">Paused</button></td>
                        <td><button class="ps-setting">Active</button></td>
                        <td><button class="ps-setting">Disabled</button>
                        </td>
                        <td>60</td>
                        <td>$1020</td>
                        <td>In Stock</td>
                        <td>$17</td>
                        <td>
                            <button data-toggle="tooltip" title="Edit" class="pd-setting-ed"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                            <button data-toggle="tooltip" title="Trash" class="pd-setting-ed"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                        </td>
                    </tr>
                </table>
                <div class="custom-pagination">
                    <ul class="pagination">
                        <li ng-if="product_list.current_page!=1" class="page-item"><a class="page-link btn" ng-click="load_product_list(product_list.current_page-1)">Previous</a></li>
                        <li ng-if="product_list.current_page==1" class="page-item"><a class="page-link btn" ng-click="load_product_list(product_list.current_page-1)" disabled>Previous</a></li>
                        <li class="page-item" ng-repeat="page in product_list.total_page">
                        <a class="page-link btn" ng-click="load_product_list(page)"><%page%></a>
                        </li>
                        <li ng-if="product_list.current_page!=product_list.last_page" class="page-item"><a class="page-link btn" ng-click="load_product_list(product_list.current_page+1)">Next</a></li>
                        <li ng-if="product_list.current_page==product_list.last_page" class="page-item"><a class="page-link btn" ng-click="load_product_list(product_list.current_page+1)" disabled>Next</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>