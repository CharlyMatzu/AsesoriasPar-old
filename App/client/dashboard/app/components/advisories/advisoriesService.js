angular.module("Dashboard").service('AdvisoriesService', function($http, RequestFactory){
    

    this.getAdvisories = function(successCallback, errorCallback){
        RequestFactory.makeTokenRequest(
            'GET',
            "/advisories",
            null,
            AuthFactory.getToken(),
            successCallback,
            errorCallback
        );
    }


    //-------------Assign
    this.getSubjectAdvisers_Ignore = function(subject_id, student_id, successCallback, errorCallback){
        RequestFactory.makeTokenRequest(
            'GET',
            "/subjects/"+subject_id+"/advisers/ignore/"+student_id,
            null,
            AuthFactory.getToken(),
            successCallback,
            errorCallback
        );
    }

    this.getMatchHours = function(adviser_id, alumn_id, successCallback, errorCallback){
        RequestFactory.makeTokenRequest(
            'GET',
            "/schedule/adviser/"+adviser_id+"/alumn/"+alumn_id+"/match",
            null,
            AuthFactory.getToken(),
            successCallback,
            errorCallback
        );
    }

    
    this.getDaysAndHours = function(successCallback, errorCallback){
        RequestFactory.makeTokenRequest(
            'GET',
            "/schedule/source",
            null,
            AuthFactory.getToken(),
            successCallback,
            errorCallback
        );
    }

    
    this.assignAdviser = function(advisory_id, hours, adviser_id, successCallback, errorCallback){
        RequestFactory.makeTokenRequest(
            'POST',
            "/advisories/"+advisory_id+"/assign",
            data = {
                hours: hours,
                adviser: adviser_id
            },
            AuthFactory.getToken(),
            successCallback,
            errorCallback
        );
    }
    

});