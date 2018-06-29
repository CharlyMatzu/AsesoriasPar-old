angular.module("Desktop")
    .service('SubjestsService', function($http, RequestFactory, AuthFactory){

    
    this.updateScheduleSubjects = function(schedule_id, subjects){
        return RequestFactory.makeTokenRequest(
            'PUT',
            "/schedule/"+schedule_id+"/subjects",
            data = {
                subjects: subjects
            },
            AuthFactory.getToken()
        );
    };
    
    
    this.getAvailableSubjects = function(schedule_id){
        return RequestFactory.makeTokenRequest(
            'GET',
            "/schedule/"+schedule_id+"/subjects/availables",
            null,
            AuthFactory.getToken()
        );
    };

});