angular.module("Dashboard").service('PeriodsService', function( RequestFactory, AuthFactory){

    this.getPeriods = function(){
        return RequestFactory.makeTokenRequest(
            'GET',
            "/periods",
            null,
            AuthFactory.getToken()
        );
    }

    this.addPeriod = function(period){
        return RequestFactory.makeTokenRequest(
            'POST',
            "/periods",
            data = {
                start: period.start,
                end: period.end
            },
            AuthFactory.getToken()
        );
    }

    this.updatePeriod = function(period){
        return RequestFactory.makeTokenRequest(
            'PUT',
            "/periods/"+period.id,
            data = {
                start: period.start,
                end: period.end
            },
            AuthFactory.getToken()
        );
    }
    
    this.changeStatus = function(period_id, status){
        return RequestFactory.makeTokenRequest(
            'PATCH',
            "/periods/"+period_id+"/status/"+status,
            null,
            AuthFactory.getToken()
        );
    }

    this.deletePeriod = function(period_id){
        return RequestFactory.makeTokenRequest(
            'DELETE',
            "/periods/"+period_id,
            null,
            AuthFactory.getToken()
        );
    }
    
    

});