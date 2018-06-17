angular.module("LoginApp").service('ConfirmService', function($http, RequestFactory){

    this.confirm = function(token, successCallback, errorCallback){
        //TODO: agregar encabezado para auth
        $http({
            method: 'GET',
            url: RequestFactory.getURL()+"/token"
        }).then(function (success){
            successCallback(success);
        },function (error){
            errorCallback(error);
        });
    }

});