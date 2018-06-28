angular.module("Dashboard").service('PlansService', function( RequestFactory, AuthFactory){

  
    this.getPlans = function(){
        return RequestFactory.makeTokenRequest(
            'GET',
            "/plans",
            null,
            AuthFactory.getToken()
        );
    }

    this.insertPlan = function(plan){
        return RequestFactory.makeTokenRequest(
            'POST',
            "/plans",
            data = {
                year: plan
            },
            AuthFactory.getToken()
        );
    }

    this.updatePlan = function(plan){
        return RequestFactory.makeTokenRequest(
            'PUT',
            "/plans/"+plan.id,
            data = {
                year: plan.year
            },
            AuthFactory.getToken()
        );
    }
    
    this.deletePlan = function(plan_id){
        return RequestFactory.makeTokenRequest(
            'DELETE',
            "/plans/"+plan_id,
            null,
            AuthFactory.getToken()
        );
    }


    
    this.changeStatus = function(plan_id, status){
        return RequestFactory.makeTokenRequest(
            'PATCH',
            "/plans/"+plan_id+"/status/"+status,
            null,
            AuthFactory.getToken()
        );
    }


    this.getCurrentPlan = function(){
        return RequestFactory.makeTokenRequest(
            'GET',
            "/periods/current",
            null,
            AuthFactory.getToken()
        );
    }


    
    

});