angular.module("Desktop").service('AdvisoriesService', function($http, RequestFactory, AuthFactory){

    
    this.getRequestedAdvisories = function(student_id){
        return RequestFactory.makeTokenRequest(
            'GET',
            "/students/"+student_id+"/advisories/requested",
            null,
            AuthFactory.getToken()
        );
    }


    this.getSubjects = function(){
        return RequestFactory.makeTokenRequest(
            'GET',
            "/subjects/enabled",
            null,
            AuthFactory.getToken()
        );
    } 

    this.requestAdvisory = function(advisory){
        return RequestFactory.makeTokenRequest(
            'POST',
            "/students/"+advisory.student+"/advisories",
            advisory,
            AuthFactory.getToken()
        );
    }

    this.finalizeAdvisory = function(advisory_id){
        return RequestFactory.makeTokenRequest(
            'PATCH',
            "/advisories/"+advisory_id+"/finalize",
            null,
            AuthFactory.getToken()
        );
    } 


});