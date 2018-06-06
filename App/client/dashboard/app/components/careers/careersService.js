app.service('CareerService', function($http){
    

    this.getCareers = function(successCallback, errorCallback){
        $http({
            method: 'GET',
            url: "http://api.ronintopics.com/index.php/careers"
        }).then(function(success){
            successCallback(success);
        }, function(error){
            errorCallback(error);
        });
    }

    this.addCareer = function(career, successCallback, errorCallback){
        $http({
            method: 'POST',
            url: "http://api.ronintopics.com/index.php/careers",
            data: {
                name: career.name,
                short_name: career.short_name
            }
        }).then(function(success){
            successCallback(success) 
        }, function(error){
            errorCallback(error)
        });
    }

    this.updateCareer = function(career, successCallback, errorCallback){
        $http({
            method: 'PUT',
            url: "http://api.ronintopics.com/index.php/careers/"+career.id,
            data: {
                name: career.name,
                short_name: career.short_name
            }
        }).then(function (success){
            successCallback(success);
        },function (error){
            errorCallback(error);
        });
    }
    
    this.changeStatus = function(career_id, status, successCallback, errorCallback){
        $http({
            method: 'PATCH',
            url: "http://api.ronintopics.com/index.php/careers/"+career_id+"/status/"+status
        }).then(function (success){
            successCallback(success);
        },function (error){
            errorCallback(error);
        });
    }

    this.deleteCareer = function(career_id, successCallback, errorCallback){
        $http({
            method: 'DELETE',
            url: "http://api.ronintopics.com/index.php/careers/"+career_id
        }).then(function (success){
            successCallback(success);
        },function (error){
            errorCallback(error);
        });
    }
    
    

});