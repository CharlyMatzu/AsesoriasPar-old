app.service('AdvisoriesService', function($http, RequestFactory){

    this.getCurrentPeriod = function(successCallback, errorCallback){
        //Obteniendo periodo actual
        $http({
            method: 'GET',
            url: RequestFactory.getURL()+"/periods/current"
        }).then(function(success){
            successCallback(success);
        }, function(error){
            errorCallback(error);
        });
    }
    

    this.getAdvisories = function(successCallback, errorCallback){
        $http({
            method: 'GET',
            url: RequestFactory.getURL()+"/careers"
        }).then(function(success){
            successCallback(success);
        }, function(error){
            errorCallback(error);
        });
    } 

});