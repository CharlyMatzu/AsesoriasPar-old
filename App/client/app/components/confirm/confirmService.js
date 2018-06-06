app.service('ConfirmService', function($http){

    this.confirm = function(token, successCallback, errorCallback){
        //TODO: agregar encabezado para auth
        $http({
            method: 'GET',
            url: "http://api.ronintipics.com/index.php/users/confirm/"+token
        }).then(function (success){
            successCallback(success);
        },function (error){
            errorCallback(error);
        });
    }

});