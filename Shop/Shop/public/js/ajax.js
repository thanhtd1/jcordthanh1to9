angular.module('MyApp')
  .service("AjaxService", ['$http', function($http) {
    this.get = function(url){
      return $http({
          method: 'GET',
          url: url,
        }).then(function successCallback(response) {
            var  data = response.data;
            return data;
          }, function errorCallback(response) {
            var  data = response.data;
            return data;
          });
    }
    this.post = function(){      
      var url = arguments[0];
      var data = arguments[1];
      return $http({
          method: 'POST',
          url: url,
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          data:data
        }).then(function successCallback(response) {
            return {data:response.data,status:response.status};
          }, function errorCallback(response) {
            return {data:response.data,status:response.status}
          });   
    }
}]);