angular.module("Dashboard").service('CareerService', function($http, RequestFactory, AuthFactory){
    

    this.getCareers = function(successCallback, errorCallback){
        RequestFactory.makeTokenRequest(
            'GET',
            "/careers",
            null,
            AuthFactory.getToken(),
            successCallback,
            errorCallback
        );
    }

    this.addCareer = function(career, successCallback, errorCallback){
        RequestFactory.makeTokenRequest(
            'POST',
            "/careers",
            data = {
                name: career.name,
                short_name: career.short_name
            },
            AuthFactory.getToken(),
            successCallback,
            errorCallback
        );
    }

    this.updateCareer = function(career, successCallback, errorCallback){
        RequestFactory.makeTokenRequest(
            'PUT',
            "/careers"+career.id,
            data = {
                name: career.name,
                short_name: career.short_name
            },
            AuthFactory.getToken(),
            successCallback,
            errorCallback
        );
    }
    
    this.changeStatus = function(career_id, status, successCallback, errorCallback){
        RequestFactory.makeTokenRequest(
            'PATCH',
            "/careers/"+career_id+"/status/"+status,
            null,
            AuthFactory.getToken(),
            successCallback,
            errorCallback
        );
    }

    this.deleteCareer = function(career_id, successCallback, errorCallback){
        RequestFactory.makeTokenRequest(
            'DELETE',
            "/careers/"+career_id,
            null,
            AuthFactory.getToken(),
            successCallback,
            errorCallback
        );
    }
    
    

});