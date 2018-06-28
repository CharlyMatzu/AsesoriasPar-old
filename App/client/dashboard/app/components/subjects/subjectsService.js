angular.module("Dashboard").service('SubjectService', function( RequestFactory, AuthFactory){


    this.getCareers = function(){
        return RequestFactory.makeTokenRequest(
            'GET',
            "/careers",
            null,
            AuthFactory.getToken()
        );
    }

    this.getPlans = function(){
        return RequestFactory.makeTokenRequest(
            'GET',
            "/plans",
            null,
            AuthFactory.getToken()
        );
    }
    

    this.getSubjects = function(){
        return RequestFactory.makeTokenRequest(
            'GET',
            "/subjects",
            null,
            AuthFactory.getToken()
        );
    }
    
    this.getSubject_Search = function(subject){
        return RequestFactory.makeTokenRequest(
            'GET',
            "/subjects/carrera/"+subject.career+"/semestre/"+subject.semester+"/plan/"+subject.plan,
            null,
            AuthFactory.getToken()
        );
    }
    
    this.searchSubjects = function(data){ 
        return RequestFactory.makeTokenRequest(
            'GET',
            "/subjects/search/"+data,
            null,
            AuthFactory.getToken()
        );
    } 


    this.updateSubject = function(subject){
        return RequestFactory.makeTokenRequest(
            'PUT',
            "/subjects/"+subject.id,
            data = {
                name: subject.name,
                short_name: subject.short_name,
                description: subject.description,
                career: subject.career_id,
                semester: subject.semester,
                plan: subject.plan_id
            },
            AuthFactory.getToken()
        );
    }
    
    this.changeStatus = function(subject_id, status){
        return RequestFactory.makeTokenRequest(
            'PATCH',
            "/subjects/"+subject_id+"/status/"+status,
            null,
            AuthFactory.getToken()
        );
    }

    this.deleteSubject = function(subject_id){
        return RequestFactory.makeTokenRequest(
            'PATCH',
            "/subjects/"+subject_id,
            null,
            AuthFactory.getToken()
        );
    }
    
    

});