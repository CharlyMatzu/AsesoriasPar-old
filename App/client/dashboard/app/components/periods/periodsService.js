
app.service('PeriodsService', function($http){
    // this.data = [];
    this.getPeriods = function(successCallback, errorCallback){
        $http({
            method: 'GET',
            url: "http://api.ronintopics.com/index.php/periods"
        }).then(function(success){
            successCallback(success);
        }, function(error){
            errorCallback(error);
        });
    }

    this.addPeriod = function(period, successCallback, errorCallback){
        $http({
            method: 'POST',
            url: "http://api.ronintopics.com/index.php/periods",
            data: {
                start: period.start,
                end: period.end
            }
        }).then(function(success){
            successCallback(success) 
        }, function(error){
            errorCallback(error)
        });
    }

    this.updatePeriod = function(period, successCallback, errorCallback){
        $http({
            method: 'PUT',
            url: "http://api.ronintopics.com/index.php/periods/"+period.id,
            data: {
                start: period.start,
                end: period.end
            }
        }).then(function (success){
            successCallback(success);
        },function (error){
            errorCallback(error);
        });
    }
    
    this.changeStatus = function(period_id, status, successCallback, errorCallback){
        $http({
            method: 'PATCH',
            url: "http://api.ronintopics.com/index.php/periods/"+period_id+"/status/"+status
        }).then(function (success){
            successCallback(success);
        },function (error){
            errorCallback(error);
        });
    }

    this.deletePeriod = function(period_id, successCallback, errorCallback){
        $http({
            method: 'DELETE',
            url: "http://api.ronintopics.com/index.php/periods/"+period_id
        }).then(function (success){
            successCallback(success);
        },function (error){
            errorCallback(error);
        });
    }
    
    

});