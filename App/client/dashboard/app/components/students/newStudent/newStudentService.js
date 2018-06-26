angular.module("Dashboard").service('NewUserService', function($http, RequestFactory, AuthFactory){

    
    this.addStudent = function(student){
        return RequestFactory.makeTokenRequest(
            'POST',
            "/auth/signUp",
            data = {
                email: student.email,
                password: student.pass,
                
                first_name: student.first_name,
                last_name: student.last_name,
                itson_id: student.itson_id,
                phone: student.phone,
                facebook: student.facebook,
                career: student.career,
            },
            AuthFactory.getToken(),
        );
    }
    

});