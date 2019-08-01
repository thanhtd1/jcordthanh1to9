angular.module('MyApp', ['ngAnimate','ngMaterial','ngMessages'], function($interpolateProvider) {
  $interpolateProvider.startSymbol('<%');
  $interpolateProvider.endSymbol('%>');
})
.controller("AppCtrl", ['AjaxService','$scope', '$mdDialog','$mdToast', function(ajax, $scope, $mdDialog, $mdToast) {
  var l_this = this;
  this.myDate = new Date();
  this.minDate = this.myDate;
  this.maxDate = new Date(
    this.myDate.getFullYear(),
    this.myDate.getMonth(),
    this.myDate.getDate() + 2
  );
  this.contentsBase = 'admin/view/product-list';
  $scope.product_list='';
  $scope.showForm = function(ev,form_name) {
    $mdDialog.show({
      controller: DialogController,
      targetEvent:ev ,
      templateUrl :'/element/form/'+form_name,
   });
  };
  $scope.showNotification = function(messenger) {
    messenger=messenger[0];
    $mdToast.show(
      $mdToast.simple()
        .textContent(messenger)
        .position('top left')
        .hideDelay(10000)
    );
  };
  $scope.load_product_list= function(url='') {
    if(url!=''){
      var product_list =ajax.get('admin/view/list_product?page='+url);
    }
    else{        
      var product_list =ajax.get('admin/view/list_product');
    }
    product_list.then(function (reponse){
      var arr=[];
      for(i=1;i<=reponse.last_page;i++){
        arr.push(i);
      }
      reponse.total_page = arr;
      $scope.product_list = reponse;
    });
  };
  $scope.add_product = function(){
    var product = ajax.post('admin/view/add_product',$scope.add_product_item);
    product.then(function (rep){
      if(rep.status==400){
        $scope.error_product_price    = rep.data.price;
        $scope.error_product_name     = rep.data.name;
        $scope.error_product_type     = rep.data.type;
        $scope.error_product_quantity = rep.data.quantity;
      }
      else{
        $mdDialog.hide();
        console.log($scope.product_list);
        $scope.product_list=rep.data;
        console.log($scope.product_list);

        l_this.load_content('admin/view/list_product');
      }
    });
  };
  this.load_content = function(url){    
    this.contentsBase = url;
  }
  function DialogController($scope, $mdDialog) {  
    $scope.cancel = function() {
      $mdDialog.cancel();
    };
  }  
}]);