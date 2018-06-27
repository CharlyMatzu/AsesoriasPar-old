angular.module("Dashboard").service('CareerService', function($http, RequestFactory, AuthFactory){
    

    this.getCareers = function(){
        return RequestFactory.makeTokenRequest(
            'GET',
            "/careers",
            null,
            AuthFactory.getToken()
        );
    }

    this.addCareer = function(career){
        return RequestFactory.makeTokenRequest(
            'POST',
            "/careers",
            data = {
                name: career.name,
                short_name: career.short_name
            },
            AuthFactory.getToken()
        );
    }

    this.updateCareer = function(career){
        return RequestFactory.makeTokenRequest(
            'PUT',
            "/careers/"+career.id,
            data = {
                name: career.name,
                short_name: career.short_name
            },
            AuthFactory.getToken()
        );
    }
    
    this.changeStatus = function(career_id, status){
        return RequestFactory.makeTokenRequest(
            'PATCH',
            "/careers/"+career_id+"/status/"+status,
            null,
            AuthFactory.getToken()
        );
    }

    this.deleteCareer = function(career_id){
        return RequestFactory.makeTokenRequest(
            'DELETE',
            "/careers/"+career_id,
            null,
            AuthFactory.getToken()
        );
    }
    
    

});