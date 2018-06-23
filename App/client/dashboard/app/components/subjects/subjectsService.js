angular.module("Dashboard").service('SubjectService', function($http, RequestFactory, AuthFactory){


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

    this.getPlans = function(successCallback, errorCallback){
        RequestFactory.makeTokenRequest(
            'GET',
            "/plans",
            null,
            AuthFactory.getToken(),
            successCallback,
            errorCallback
        );
    }
    

    this.getSubjects = function(successCallback, errorCallback){
        RequestFactory.makeTokenRequest(
            'GET',
            "/subjects",
            null,
            AuthFactory.getToken(),
            successCallback,
            errorCallback
        );
    }
    
    this.getSubject_Search = function(subject,successCallback, errorCallback){
        RequestFactory.makeTokenRequest(
            'GET',
            "/subjects/carrera/"+subject.career+"/semestre/"+subject.semester+"/plan/"+subject.plan,
            null,
            AuthFactory.getToken(),
            successCallback,
            errorCallback
        );
    }
    
    this.searchSubjects = function(data,successCallback, errorCallback){ 
        RequestFactory.makeTokenRequest(
            'GET',
            "/subjects/search/"+data,
            null,
            AuthFactory.getToken(),
            successCallback,
            errorCallback
        );
    } 


    this.updateSubject = function(subject, successCallback, errorCallback){
        RequestFactory.makeTokenRequest(
            'PUT',
            "/subjects",
            data = {
                name: subject.name,
                short_name: subject.short_name,
                description: subject.description,
                career: subject.career_id,
                semester: subject.semester,
                plan: subject.plan
            },
            AuthFactory.getToken(),
            successCallback,
            errorCallback
        );
    }
    
    this.changeStatus = function(subject_id, status, successCallback, errorCallback){
        RequestFactory.makeTokenRequest(
            'PATCH',
            "/subjects/"+subject_id+"/status/"+status,
            null,
            AuthFactory.getToken(),
            successCallback,
            errorCallback
        );
    }

    this.deleteSubject = function(subject_id, successCallback, errorCallback){
        RequestFactory.makeTokenRequest(
            'PATCH',
            "/subjects/"+subject_id,
            null,
            AuthFactory.getToken(),
            successCallback,
            errorCallback
        );
    }
    
    

});