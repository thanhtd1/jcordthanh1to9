<md-dialog flex="30"  ng-app="MyApp" ng-controller="AppCtrl as ctrl">
    {{-- <form ng-cloak action="{{url('admin/add_product')}}" method="POST" autocomplete="false" >
    @csrf --}}
    <md-toolbar flex="100">
      <div class="md-toolbar-tools">
        <div layout="row" layout-align="center center">
                <h4>Thêm sản phẩm</h4>
        </div>
      <span flex></span>
      <md-button class="md-icon-button" ng-click="cancel()">
        X
      </md-button>
      </div>
    </md-toolbar>
          
    <md-dialog-content layout="column" layout-align="center stretch" ><br>
      <md-input-container  flex="90">
        <label>Tên sản phẩm</label>
        <input required ng-model="add_product_item.name" >
        <div ng-if="error_product_name" class="text-danger"><% error_product_name %></div>    
        </md-input-container>    
        <md-input-container flex="100">
          <label>Loại sản phẩm</label>
          <md-select required ng-model="add_product_item.type" required>
            @foreach ($types as $item)
              <md-option value="{{$item->id}}">{{$item->name}}</md-option>
            @endforeach
          </md-select>
          <div ng-if="error_product_type" class="text-danger"><% error_product_type%></div>
        </md-input-container>
        <md-input-container  flex="90">
          <label>Số lượng</label>
          <input required type="number" ng-model="add_product_item.quantity "min="1"/>
          <div ng-if="error_product_quantity" class="text-danger"><% error_product_quantity %></div>
        </md-input-container>
        <md-input-container  flex="90">
          <label>Giá bán</label>
          <input required type="number" ng-model="add_product_item.price" min="1000" value="1000"/>
          <div ng-if="error_product_price" class="text-danger"><% error_product_price %></div>
        </md-input-container>
    </md-dialog-content>
    <md-dialog-actions layout="row" layout-align="end center">
      <md-button class="md-button md-primary" ng-click="add_product()">Thêm</md-button>
      <md-button class="md-accent" ng-click="cancel()">Huỷ</md-button>
    </md-dialog-actions>
    </md-dialog>