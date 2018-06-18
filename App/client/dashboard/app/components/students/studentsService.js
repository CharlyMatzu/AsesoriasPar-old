app.service('StudentsService', function($http, RequestFactory){

    
    this.getStudents = function(successCallback, errorCallback){
        $http({
            method: 'GET',
            url: RequestFactory.getURL()+"/students"
        }).then(function(success){
            successCallback(success);
        }, function(error){
            errorCallback(error);
        });
    }


    this.searchStudents = function(data,successCallback, errorCallback){
        $http({
            method: 'GET',
            url: RequestFactory.getURL()+"/students/search/"+data
        }).then(function(success){
            successCallback(success);
        }, function(error){
            errorCallback(error);
        });
    }


    this.changeStatus = function(user_id, status, successCallback, errorCallback){
        $http({
            method: 'PATCH',
            url: RequestFactory.getURL()+"/users/"+user_id+"/status/"+status
        }).then(function(success){
            successCallback(success);
        }, function(error){
            errorCallback(error);
        });
    }


    this.deleteStudent = function(user_id, successCallback, errorCallback){
        $http({
            method: 'DELETE',
            url: RequestFactory.getURL()+"/users/"+user_id
        }).then(function (success){
            successCallback(success);
        },function (error){
            errorCallback(error);
        });
    }
    

});