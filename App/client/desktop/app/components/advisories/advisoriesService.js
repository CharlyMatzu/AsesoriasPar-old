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
    

    this.getRequestedAdvisories = function(student_id, successCallback, errorCallback){
        $http({
            method: 'GET',
            url: RequestFactory.getURL()+"/student/"+student_id+"/advisories/requested"
        }).then(function(success){
            successCallback(success);
        }, function(error){
            errorCallback(error);
        });
    }

    this.getAdviserAdvisories = function(student_id, successCallback, errorCallback){
        $http({
            method: 'GET',
            url: RequestFactory.getURL()+"/student/"+student_id+"/advisories/adviser"
        }).then(function(success){
            successCallback(success);
        }, function(error){
            errorCallback(error);
        });
    }

});