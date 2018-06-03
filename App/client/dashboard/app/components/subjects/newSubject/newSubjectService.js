app.service('NewSubjectService', function($http){


    this.getCareers = function(successCallback, errorCallback){
        $http({
            method: 'GET',
            url: "http://asesoriaspar.ronintopics.com/index.php/careers"
        }).then(function(success){
            successCallback(success);
        }, function(error){
            errorCallback(error);
        });
    }


    
    this.getPlans = function(successCallback, errorCallback){
        $http({
            method: 'GET',
            url: "http://asesoriaspar.ronintopics.com/index.php/plans"
        }).then(function(success){
            successCallback(success);
        }, function(error){
            errorCallback(error);
        });
    }
    



    this.addSubject = function(subject, successCallback, errorCallback){
        $http({
            method: 'POST',
            url: "http://asesoriaspar.ronintopics.com/index.php/subjects",
            data: {
                name: subject.name,
                short_name: subject.short_name,
                description: subject.description,
                career: subject.career,
                semester: subject.semester,
                plan: subject.plan
            }
        }).then(function(success){
            successCallback(success) 
        }, function(error){
            errorCallback(error)
        });
    }
    

});