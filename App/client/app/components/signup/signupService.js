angular.module("LoginApp").service('SignupService', function($http, RequestFactory){
    
    this.getCareers = function(successCallback, errorCallback){
        return RequestFactory.makeRequest(
            'GET',
            "/careers"
        );
    }

    this.signup = function(student, successCallback, errorCallback){
        return RequestFactory.makeRequest(
            'GET',
            "/auth/signup",
            data = {
                email: student.email,
                password: student.pass,
                first_name: student.first_name,
                last_name: student.last_name,
                career: student.career,
                itson_id: student.itson_id,
                phone: student.phone
            }
        );
    }

});