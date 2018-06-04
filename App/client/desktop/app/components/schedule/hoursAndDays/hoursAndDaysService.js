app.service('HoursAndDaysService', function($http){

    this.getCurrentPeriod = function(successCallback, errorCallback){
        $http({
            method: 'GET',
            url: "http://api.asesoriaspar.com/index.php/periods/current"
        }).then(function(success){
            successCallback(success);
        }, function(error){
            errorCallback(error);
        });
    } 
    

    this.getDaysAndHours = function(successCallback, errorCallback){
        $http({
            method: 'GET',
            url: "http://api.asesoriaspar.com/index.php/schedule/source"
        }).then(function(success){
            successCallback(success);
        }, function(error){
            errorCallback(error);
        });
    } 


    this.getStudentSchedule = function(student_id, successCallback, errorCallback){
        $http({
            method: 'GET',
            url: "http://api.asesoriaspar.com/index.php/students/"+student_id+"/schedule"
        }).then(function(success){
            successCallback(success);
        }, function(error){
            errorCallback(error);
        });
    } 

});