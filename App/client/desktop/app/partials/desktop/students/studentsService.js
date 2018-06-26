angular.module("Desktop").service('StudentsService', function($http, RequestFactory, AuthFactory){

    this.getAdviserAdvisories = function(student_id){
        return RequestFactory.makeTokenRequest(
            'GET',
            "/student/"+student_id+"/advisories/adviser",
            null,
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