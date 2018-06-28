angular.module("Desktop").service('ProfileService', function($http, RequestFactory, AuthFactory){

    
    this.updateStudent = function(student){
        return RequestFactory.makeTokenRequest(
            'PUT',
            "/students/"+student.id,
            data = {
                career: student.career_id,
                first_name: student.first_name,
                last_name: student.last_name,
                itson_id: student.itson_id,
                phone: student.phone,
                facebook: student.facebook,
                email: student.user_email
            },
            AuthFactory.getToken()
        );
    }

    this.updatePassword = function(user_id, pass){
        return RequestFactory.makeTokenRequest(
            'PUT',
            "/auth/"+user_id+"/password",
            data = {
                old: pass.old,
                new: pass.new
            },
            AuthFactory.getToken()
        );
    } 


});