angular.module("Desktop").service('ScheduleService', function($http, RequestFactory, AuthFactory){


    this.getCurrentPeriod = function(){
        return RequestFactory.makeTokenRequest(
            'GET',
            "/periods/current",
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


    this.getStudentSchedule = function(student_id){
        return RequestFactory.makeTokenRequest(
            'GET',
            "/students/"+student_id+"/schedule",
            null,
            AuthFactory.getToken()
        );
    }

    this.createSchedule = function(student_id){
        return RequestFactory.makeTokenRequest(
            'POST',
            "/students/"+student_id+"/schedule",
            null,
            AuthFactory.getToken()
        );
    } 


    this.updateScheduleHours = function(schedule_id, hours){
        return RequestFactory.makeTokenRequest(
            'PUT',
            "/schedule/"+schedule_id+"/hours",
            null,
            AuthFactory.getToken()
        );
    } 


    this.updateScheduleSubjects = function(schedule_id, subjects){
        return RequestFactory.makeTokenRequest(
            'PUT',
            "/schedule/"+schedule_id+"/subjects",
            null,
            AuthFactory.getToken()
        );
    }


    
    this.getSubjects = function(){
        return RequestFactory.makeTokenRequest(
            'GET',
            "/subjects/enabled",
            null,
            AuthFactory.getToken()
        );
    } 

});