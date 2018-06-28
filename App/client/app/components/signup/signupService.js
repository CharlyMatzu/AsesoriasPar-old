angular.module("LoginApp").service('SignupService', function($http, RequestFactory){
    
    this.getCareers = function(){
        return RequestFactory.makeRequest(
            'GET',
            "/careers"
        );
    }

    this.signup = function(student){
        return RequestFactory.makeRequest(
            'POST',
            "/auth/signup",
            data = {
                email: student.email,
                password: student.pass,
                first_name: student.first_name,
                last_name: student.last_name,
                career: student.career,
                itson_id: student.itson_id,
                phone: student.phone,
                facebook: student.facebook
            }
        );
    }

});