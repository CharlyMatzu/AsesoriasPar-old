angular.module("Dashboard").service('StudentDetailService', function($http, RequestFactory, AuthFactory){

    
    this.getStudent = function(student_id, successCallback, errorCallback){
        RequestFactory.makeTokenRequest(
            'GET',
            "/students/"+student_id,
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


    this.getStudentSchedule = function(student_id, successCallback, errorCallback){
        RequestFactory.makeTokenRequest(
            'GET',
            "/students/"+student_id+"/schedule",
            null,
            AuthFactory.getToken(),
            successCallback,
            errorCallback
        );
    }


    this.changeStatus = function(user_id, status, successCallback, errorCallback){
        RequestFactory.makeTokenRequest(
            'PATCH',
            "/users/"+user_id+"/status/"+status,
            null,
            AuthFactory.getToken(),
            successCallback,
            errorCallback
        );
    }


    this.deleteStudent = function(user_id, successCallback, errorCallback){
        RequestFactory.makeTokenRequest(
            'DELETE',
            "/users/"+user_id,
            null,
            AuthFactory.getToken(),
            successCallback,
            errorCallback
        );
    }
    

});