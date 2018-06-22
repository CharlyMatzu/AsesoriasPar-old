angular.module("Dashboard").service('PlansService', function($http, RequestFactory, AuthFactory){

  
    this.getPlans = function(successCallback, errorCallback){
        RequestFactory.makeTokenRequest(
            'GET',
            "/plans",
            null,
            AuthFactory.getToken(),
            successCallback,
            errorCallback
        );
    }

    this.insertPlan = function(plan, successCallback, errorCallback){
        RequestFactory.makeTokenRequest(
            'POST',
            "/plans",
            data = {
                year: plan
            },
            AuthFactory.getToken(),
            successCallback,
            errorCallback
        );
    }

    this.updatePlan = function(plan, successCallback, errorCallback){
        RequestFactory.makeTokenRequest(
            'PUT',
            "/plans/"+plan.id,
            data = {
                year: plan.year
            },
            AuthFactory.getToken(),
            successCallback,
            errorCallback
        );
    }
    
    this.deletePlan = function(plan_id, successCallback, errorCallback){
        RequestFactory.makeTokenRequest(
            'DELETE',
            "/plans/"+plan_id,
            null,
            AuthFactory.getToken(),
            successCallback,
            errorCallback
        );
    }


    
    this.changeStatus = function(plan_id, status, successCallback, errorCallback){
        RequestFactory.makeTokenRequest(
            'PATCH',
            "/plans/"+plan_id+"/status/"+status,
            null,
            AuthFactory.getToken(),
            successCallback,
            errorCallback
        );
    }


    
    

});