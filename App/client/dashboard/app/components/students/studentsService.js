angular.module("Dashboard").service('StudentsService', function( RequestFactory, AuthFactory){

    
    this.getStudents = function(){
        return RequestFactory.makeTokenRequest(
            'GET',
            "/students",
            null,
            AuthFactory.getToken()
        );
    }


    this.searchStudents = function(data){
        return RequestFactory.makeTokenRequest(
            'GET',
            "/students/search/"+data,
            null,
            AuthFactory.getToken()
        );
    }


    this.changeStatus = function(user_id, status){
        return RequestFactory.makeTokenRequest(
            'PATCH',
            "/users/"+user_id+"/status/"+status,
            null,
            AuthFactory.getToken()
        );
    }


    this.deleteStudent = function(user_id){
        return RequestFactory.makeTokenRequest(
            'DELETE',
            "/users/"+user_id,
            null,
            AuthFactory.getToken()
        );
    }
    

});