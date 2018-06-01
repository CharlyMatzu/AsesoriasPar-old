app.service('PlansService', function($http){

  
    this.getPlans = function(successCallback, errorCallback){
        $http({
            method: 'GET',
            url: "http://client.asesoriaspar.com/index.php/plans"
        }).then(function(success){
            successCallback(success);
        }, function(error){
            errorCallback(error);
        });
    }

    this.addPlan = function(plan, successCallback, errorCallback){
        $http({
            method: 'POST',
            url: "http://client.asesoriaspar.com/index.php/index.php/plans",
            data: {
                year: plan.year
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
            url: "http://client.asesoriaspar.com/index.php/index.php/plans/"+plan.plan_id,
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
            url: "http://client.asesoriaspar.com/index.php/index.php/plans/"+plan_id
        }).then(function (success){
            successCallback(success);
        },function (error){
            errorCallback(error);
        });
    }
    
    

});