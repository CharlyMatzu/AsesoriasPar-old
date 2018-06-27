angular.module("Dashboard").service('NewSubjectService', function( RequestFactory, AuthFactory){


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
    



    this.addSubject = function(subject){
        return RequestFactory.makeTokenRequest(
            'POST',
            "/subjects",
            data = {
                name:           subject.name,
                short_name:     subject.short_name,
                description:    subject.description,
                career:         subject.career_id,
                semester:       subject.semester,
                plan:           subject.plan_id
            },
            AuthFactory.getToken()
        );
    }
    

});