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


    //-------------Assign
    this.getSubjectAdvisers_Ignore = function(subject_id, student_id, successCallback, errorCallback){
        $http({
            method: 'GET',
            url: RequestFactory.getURL()+"/subjects/"+subject_id+"/advisers/ignore/"+student_id
        }).then(function(success){
            successCallback(success);
        }, function(error){
            errorCallback(error);
        });
    }

    this.getMatchHours = function(adviser_id, alumn_id, successCallback, errorCallback){
        $http({
            method: 'GET',
            url: RequestFactory.getURL()+"/schedule/adviser/"+adviser_id+"/alumn/"+alumn_id+"/match"
        }).then(function(success){
            successCallback(success);
        }, function(error){
            errorCallback(error);
        });
    }

    
    this.getDaysAndHours = function(successCallback, errorCallback){
        $http({
            method: 'GET',
            url: RequestFactory.getURL()+"/schedule/source"
        }).then(function(success){
            successCallback(success);
        }, function(error){
            errorCallback(error);
        });
    }

    
    this.assignAdviser = function(advisory_id, hours, adviser_id, successCallback, errorCallback){
        $http({
            method: 'POST',
            url: RequestFactory.getURL()+"/advisories/"+advisory_id+"/assign",
            data: {
                hours: hours,
                adviser: adviser_id
            }
        }).then(function(success){
            successCallback(success);
        }, function(error){
            errorCallback(error);
        });
    }
    

});