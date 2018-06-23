angular.module("Dashboard").service('NewSubjectService', function($http, RequestFactory, AuthFactory){


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
    



    this.addSubject = function(subject, successCallback, errorCallback){
        RequestFactory.makeTokenRequest(
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
            AuthFactory.getToken(),
            successCallback,
            errorCallback
        );

        // $http({
        //     method: 'POST',
        //     url: RequestFactory.getURL()+"/subjects",
        //     data: {
        //         name: subject.name,
        //         short_name: subject.short_name,
        //         description: subject.description,
        //         career: subject.career,
        //         semester: subject.semester,
        //         plan: subject.plan
        //     }
        // }).then(function(success){
        //     successCallback(success) 
        // }, function(error){
        //     errorCallback(error)
        // });
    }
    

});