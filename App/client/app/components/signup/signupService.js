app.service('SignupService', function($http){
    
    this.getCareers = function(successCallback, errorCallback){
        $http({
            method: 'GET',
            //TODO: cambiar URL para obtener solo carreras activas
            url: "http://api.ronintopics.com/index.php/careers"
        }).then(function(success){
            successCallback(success);
        }, function(error){
            errorCallback(error);
        });
    }

    this.signup = function(student, successCallback, errorCallback){
        $http({
            method: 'POST',
            url: "http://api.ronintopics.com/index.php/users/student",
            data: {
                email: student.email,
                password: student.pass,
                first_name: student.first_name,
                last_name: student.last_name,
                career: student.career,
                itson_id: student.itson_id,
                phone: student.phone
            }
        }).then(function (success){
            successCallback(success);
        },function (error){
            errorCallback(error);
        });
    }

});