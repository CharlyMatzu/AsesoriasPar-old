app.service('SubjectService', function($http){


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
    

    this.getSubjects = function(successCallback, errorCallback){
        $http({
            method: 'GET',
            url: "http://asesoriaspar.ronintopics.com/index.php/subjects"
        }).then(function(success){
            successCallback(success);
        }, function(error){
            errorCallback(error);
        });
    }

    this.addSubjects = function(subject, successCallback, errorCallback){
        $http({
            method: 'POST',
            url: "http://asesoriaspar.ronintopics.com/index.php/subjects",
            data: {
                name: subject.name,
                short_name: subject.short_name,
                description: subject.description,
                career: subject.career_id,
                semester: subject.semester,
                plan: subject.plan
            }
        }).then(function(success){
            successCallback(success) 
        }, function(error){
            errorCallback(error)
        });
    }

    this.updateSubject = function(subject, successCallback, errorCallback){
        $http({
            method: 'PUT',
            url: "http://asesoriaspar.ronintopics.com/index.php/subjects/"+subject.id,
            data: {
                name: subject.name,
                short_name: subject.short_name,
                description: subject.description,
                career: subject.career,
                semester: subject.semester,
                plan: subject.plan
            }
        }).then(function (success){
            successCallback(success);
        },function (error){
            errorCallback(error);
        });
    }
    
    this.changeStatus = function(subject_id, status, successCallback, errorCallback){
        $http({
            method: 'PATCH',
            url: "http://asesoriaspar.ronintopics.com/index.php/subjects/"+subject_id+"/status/"+status
        }).then(function (success){
            successCallback(success);
        },function (error){
            errorCallback(error);
        });
    }

    this.deleteSubject = function(subject_id, successCallback, errorCallback){
        $http({
            method: 'DELETE',
            url: "http://asesoriaspar.ronintopics.com/index.php/subjects/"+subject_id
        }).then(function (success){
            successCallback(success);
        },function (error){
            errorCallback(error);
        });
    }
    
    

});