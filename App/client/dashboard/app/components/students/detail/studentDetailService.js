angular.module("Dashboard").service('StudentDetailService', function( RequestFactory, AuthFactory){

    
    this.getStudent = function(student_id){
        return RequestFactory.makeTokenRequest(
            'GET',
            "/students/"+student_id,
            null,
            AuthFactory.getToken()
        );
    }


    this.getDaysAndHours_source = function(){
        return RequestFactory.makeTokenRequest(
            'GET',
            "/students",
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

    this.updateStudent = function(student){
        return RequestFactory.makeTokenRequest(
            'PUT',
            "/students/"+user_id,
            data = {
                career: student.career,
                first_name: status.first_name,
                last_name: student.last_name,
                itson_id: student.itson_id,
                phone: status.phone,
                facebook: status.facebook,
                email: status.email
            },
            AuthFactory.getToken()
        );
    }
    

});