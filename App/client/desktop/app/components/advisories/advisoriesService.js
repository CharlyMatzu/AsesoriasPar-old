app.service('AdvisoriesService', function($http){
    

    this.getAdvisories = function(successCallback, errorCallback){
        $http({
            method: 'GET',
            url: "http://asesoriaspar.ronintopics.com/index.php/careers"
        }).then(function(success){
            successCallback(success);
        }, function(error){
            errorCallback(error);
        });
    } 

});