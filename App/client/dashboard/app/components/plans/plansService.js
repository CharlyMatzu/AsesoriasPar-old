app.service('PlansService', function($http){

  
    this.getPlans = function(successCallback, errorCallback){
        $http({
            method: 'GET',
            url: "http://api.ronintopics.com/index.php/plans"
        }).then(function(success){
            successCallback(success);
        }, function(error){
            errorCallback(error);
        });
    }

    this.insertPlan = function(plan, successCallback, errorCallback){
        $http({
            method: 'POST',
            url: "http://api.ronintopics.com/index.php/plans",
            data: {
                year: plan
            }
        }).then(function(success){
            successCallback(success) 
        }, function(error){
            errorCallback(error)
        });
    }

    this.updatePlan = function(plan, successCallback, errorCallback){
        $http({
            method: 'PUT',
            url: "http://api.ronintopics.com/index.php/plans/"+plan.id,
            data: {
                year: plan.year
            }
        }).then(function(success){
            successCallback(success) 
        }, function(error){
            errorCallback(error)
        });
    }
    
    this.deletePlan = function(plan_id, successCallback, errorCallback){
        $http({
            method: 'DELETE',
            url: "http://api.ronintopics.com/index.php/plans/"+plan_id
        }).then(function (success){
            successCallback(success);
        },function (error){
            errorCallback(error);
        });
    }


    
    this.changeStatus = function(plan_id, status, successCallback, errorCallback){
        $http({
            method: 'PATCH',
            url: "http://api.ronintopics.com/index.php/plans/"+plan_id+"/status/"+status
        }).then(function(success){
            successCallback(success);
        }, function(error){
            errorCallback(error);
        });
    }


    
    

});