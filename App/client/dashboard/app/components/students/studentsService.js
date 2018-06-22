angular.module("Dashboard").service('StudentsService', function($http, RequestFactory, AuthFactory){

    
    this.getStudents = function(successCallback, errorCallback){
        RequestFactory.makeTokenRequest(
            'GET',
            "/students",
            null,
            AuthFactory.getToken(),
            successCallback,
            errorCallback
        );
    }


    this.searchStudents = function(data,successCallback, errorCallback){
        RequestFactory.makeTokenRequest(
            'GET',
            "/students/search/"+data,
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