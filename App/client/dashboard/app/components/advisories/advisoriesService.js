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
 
    

});