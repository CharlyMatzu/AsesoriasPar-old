angular.module("Dashboard").service('PeriodsService', function($http, RequestFactory, AuthFactory){

    this.getPeriods = function(successCallback, errorCallback){
        RequestFactory.makeTokenRequest(
            'GET',
            "/periods",
            null,
            AuthFactory.getToken(),
            successCallback,
            errorCallback
        );
    }

    this.addPeriod = function(period, successCallback, errorCallback){
        RequestFactory.makeTokenRequest(
            'POST',
            "/periods",
            data = {
                start: period.start,
                end: period.end
            },
            AuthFactory.getToken(),
            successCallback,
            errorCallback
        );
    }

    this.updatePeriod = function(period, successCallback, errorCallback){
        RequestFactory.makeTokenRequest(
            'PUT',
            "/periods/"+period.id,
            data = {
                start: period.start,
                end: period.end
            },
            AuthFactory.getToken(),
            successCallback,
            errorCallback
        );
    }
    
    this.changeStatus = function(period_id, status, successCallback, errorCallback){
        RequestFactory.makeTokenRequest(
            'PATCH',
            "/periods/"+period_id+"/status/"+status,
            null,
            AuthFactory.getToken(),
            successCallback,
            errorCallback
        );
    }

    this.deletePeriod = function(period_id, successCallback, errorCallback){
        RequestFactory.makeTokenRequest(
            'DELETE',
            "/periods/"+period_id,
            null,
            AuthFactory.getToken(),
            successCallback,
            errorCallback
        );
    }
    
    

});