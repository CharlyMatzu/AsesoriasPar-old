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
    
    
    //TODO: obtener materias disponibles para asesorías
    this.getSubjects = function(){
        return RequestFactory.makeTokenRequest(
            'GET',
            "/subjects/enabled",
            null,
            AuthFactory.getToken()
        );
    };

});