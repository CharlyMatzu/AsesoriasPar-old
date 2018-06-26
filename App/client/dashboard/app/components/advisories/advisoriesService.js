angular.module("Dashboard").service('AdvisoriesService', function($http, RequestFactory, AuthFactory){
    

    this.getAdvisories = function(){
        return RequestFactory.makeTokenRequest(
            'GET',
            "/advisories",
            null,
            AuthFactory.getToken()
        );
    }


    //-------------Assign
    this.getSubjectAdvisers_Ignore = function(subject_id, student_id){
        return RequestFactory.makeTokenRequest(
            'GET',
            "/subjects/"+subject_id+"/advisers/ignore/"+student_id,
            null,
            AuthFactory.getToken()
        );
    }

    this.getMatchHours = function(adviser_id, alumn_id){
        return RequestFactory.makeTokenRequest(
            'GET',
            "/schedule/adviser/"+adviser_id+"/alumn/"+alumn_id+"/match",
            null,
            AuthFactory.getToken()
        );
    }

    
    this.getDaysAndHours = function(){
        return RequestFactory.makeTokenRequest(
            'GET',
            "/schedule/source",
            null,
            AuthFactory.getToken()
        );
    }

    
    this.assignAdviser = function(advisory_id, hours, adviser_id){
        return RequestFactory.makeTokenRequest(
            'POST',
            "/advisories/"+advisory_id+"/assign",
            data = {
                hours: hours,
                adviser: adviser_id
            },
            AuthFactory.getToken()
        );
    }
    

});