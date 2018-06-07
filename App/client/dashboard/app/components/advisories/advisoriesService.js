app.service('AdvisoriesService', function($http, RequestFactory){
    

    this.getAdvisories = function(successCallback, errorCallback){
        $http({
            method: 'GET',
            url: RequestFactory.getURL()+"/advisories"
        }).then(function(success){
            successCallback(success);
        }, function(error){
            errorCallback(error);
        });
    }
 
    

});