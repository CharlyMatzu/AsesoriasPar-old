angular.module("Dashboard").service('PlansService', function($http, RequestFactory){

  
    this.getPlans = function(successCallback, errorCallback){
        $http({
            method: 'GET',
            url: RequestFactory.getURL()+"/plans"
        }).then(function(success){
            successCallback(success);
        }, function(error){
            errorCallback(error);
        });
    }

    this.insertPlan = function(plan, successCallback, errorCallback){
        $http({
            method: 'POST',
            url: RequestFactory.getURL()+"/plans",
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
            url: RequestFactory.getURL()+"/plans/"+plan.id,
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
            url: RequestFactory.getURL()+"/plans/"+plan_id
        }).then(function (success){
            successCallback(success);
        },function (error){
            errorCallback(error);
        });
    }


    
    this.changeStatus = function(plan_id, status, successCallback, errorCallback){
        $http({
            method: 'PATCH',
            url: RequestFactory.getURL()+"/plans/"+plan_id+"/status/"+status
        }).then(function(success){
            successCallback(success);
        }, function(error){
            errorCallback(error);
        });
    }


    
    

});