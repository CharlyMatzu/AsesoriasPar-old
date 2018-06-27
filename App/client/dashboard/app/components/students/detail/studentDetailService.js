angular.module("Dashboard").service('StudentDetailService', function( RequestFactory, AuthFactory){

    
    this.getStudent = function(student_id){
        return RequestFactory.makeTokenRequest(
            'GET',
            "/students/"+student_id,
            null,
            AuthFactory.getToken()
        );
    }


    this.getDaysAndHours = function(){
        return RequestFactory.makeTokenRequest(
            'GET',
            "/schedule/source",
            null,
            AuthFactory.getToken()
        );
    } 


    this.getStudentSchedule = function(student_id){
        return RequestFactory.makeTokenRequest(
            'GET',
            "/students/"+student_id+"/schedule",
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