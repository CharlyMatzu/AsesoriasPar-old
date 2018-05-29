app.service('PlansService', function($http){

  
    this.getPlans = function(successCallback, errorCallback){
        $http({
            method: 'GET',
            url: "http://10.202.46.54:8080/Asesorias-Par-Web/App/server/index.php/plans"
        }).then(function(success){
            var data = success.data;
            console.log( success );
            successCallback(success);
        }, function(error){
            console.log( error );
            errorCallback(error);
        });
    }

    this.addPlan = function(plan, successCallback, errorCallback){
        $http({
            method: 'POST',
            url: "http://10.202.46.54:8080/Asesorias-Par-Web/App/server/index.php/plans",
            data: {
                year: plan.year
            }
        }).then(function(success){
            console.log( success );
            successCallback(success) 
        }, function(error){
            console.log( error );
            errorCallback(error)
        });
    }

    this.updatePlan = function(plan, successCallback, errorCallback){
        $http({
            method: 'PUT',
            url: "http://10.202.46.54:8080/Asesorias-Par-Web/App/server/index.php/plans/"+plan.plan_id,
            data: {
                year: plan.year
            }
        }).then(function(success){
            console.log( success );
            successCallback(success) 
        }, function(error){
            console.log( error );
            errorCallback(error)
        });
    }
    
    this.deletePlan = function(plan_id, successCallback, errorCallback){
        $http({
            method: 'DELETE',
            url: "http://10.202.46.54:8080/Asesorias-Par-Web/App/server/index.php/plans/"+plan_id
        }).then(function (success){
            // console.log( response.data.message );
            successCallback(success);
        },function (error){
            // console.log( response.data.message );
            errorCallback(error);
        });
    }
    
    

});