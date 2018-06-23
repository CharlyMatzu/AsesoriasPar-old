app.service('ScheduleService', function($http, RequestFactory, AuthFactory){


    this.getCurrentPeriod = function(successCallback, errorCallback){
        RequestFactory.makeTokenRequest(
            'GET',
            "/periods/current",
            null,
            AuthFactory.getToken(),
            successCallback,
            errorCallback
        );
    }
    

    this.getDaysAndHours = function(successCallback, errorCallback){
        RequestFactory.makeTokenRequest(
            'GET',
            "/schedule/source",
            null,
            AuthFactory.getToken(),
            successCallback,
            errorCallback
        );
    } 


    this.getStudentSchedule = function(student_id, successCallback, errorCallback){
        RequestFactory.makeTokenRequest(
            'GET',
            "/students/"+student_id+"/schedule",
            null,
            AuthFactory.getToken(),
            successCallback,
            errorCallback
        );
    }

    this.createSchedule = function(student_id, successCallback, errorCallback){
        RequestFactory.makeTokenRequest(
            'POST',
            "/students/"+student_id+"/schedule",
            null,
            AuthFactory.getToken(),
            successCallback,
            errorCallback
        );
    } 


    this.updateScheduleHours = function(schedule_id, hours, successCallback, errorCallback){
        RequestFactory.makeTokenRequest(
            'PUT',
            "/schedule/"+schedule_id+"/hours",
            null,
            AuthFactory.getToken(),
            successCallback,
            errorCallback
        );
    } 


    this.updateScheduleSubjects = function(schedule_id, subjects, successCallback, errorCallback){
        RequestFactory.makeTokenRequest(
            'PUT',
            "/schedule/"+schedule_id+"/subjects",
            null,
            AuthFactory.getToken(),
            successCallback,
            errorCallback
        );
    }


    
    this.getSubjects = function(successCallback, errorCallback){
        RequestFactory.makeTokenRequest(
            'GET',
            "/subjects/enabled",
            null,
            AuthFactory.getToken(),
            successCallback,
            errorCallback
        );
    } 

});