app.service('AdvisoriesService', function($http, RequestFactory, AuthFactory){

    this.getCurrentPeriod = function(successCallback, errorCallback){
        RequestFactory.makeTokenRequest(
            'GET',
            "/periods/current",
            null,
            AuthFactory.getToken(),
            successCallback,
            errorCallback
        );
    }
    

    this.getRequestedAdvisories = function(student_id, successCallback, errorCallback){
        RequestFactory.makeTokenRequest(
            'GET',
            "/student/"+student_id+"/advisories/requested",
            null,
            AuthFactory.getToken(),
            successCallback,
            errorCallback
        );
    }

    this.getAdviserAdvisories = function(student_id, successCallback, errorCallback){
        RequestFactory.makeTokenRequest(
            'GET',
            "/student/"+student_id+"/advisories/adviser",
            null,
            AuthFactory.getToken(),
            successCallback,
            errorCallback
        );
    }


    this.getSubjects = function(successCallback, errorCallback){
        RequestFactory.makeTokenRequest(
            'GET',
            "/subjects/enabled",
            null,
            AuthFactory.getToken(),
            successCallback,
            errorCallback
        );
    } 

    this.requestAdvisory = function(advisory, successCallback, errorCallback){
        RequestFactory.makeTokenRequest(
            'POST',
            "/students/"+advisory.student+"/advisories",
            null,
            AuthFactory.getToken(),
            successCallback,
            errorCallback
        );
    }

    this.finalizeAdvisory = function(advisory_id, successCallback, errorCallback){
        RequestFactory.makeTokenRequest(
            'PATCH',
            "/advisories/"+advisory_id+"/finalize",
            null,
            AuthFactory.getToken(),
            successCallback,
            errorCallback
        );
    } 


});