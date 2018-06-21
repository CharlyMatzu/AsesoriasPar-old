angular.module("Dashboard").service('SubjectService', function($http, RequestFactory){


    this.getCareers = function(successCallback, errorCallback){
        $http({
            method: 'GET',
            url: RequestFactory.getURL()+"/careers"
        }).then(function(success){
            successCallback(success);
        }, function(error){
            errorCallback(error);
        });
    }

    this.getPlans = function(successCallback, errorCallback){
        $http({
            method: 'GET',
            url: RequestFactory.getURL()+"/plans"
        }).then(function(success){
            successCallback(success);
        }, function(error){
            errorCallback(error);
        });
    }
    

    this.getSubjects = function(successCallback, errorCallback){
        $http({
            method: 'GET',
            url: RequestFactory.getURL()+"/subjects"
        }).then(function(success){
            successCallback(success);
        }, function(error){
            errorCallback(error);
        });
    }
    this.getSubject_Search = function(subject,successCallback, errorCallback){
     
        $http({
            method: 'GET',
            url: RequestFactory.getURL()+"/subjects/carrera/"+subject.career+"/semestre/"+subject.semester+"/plan/"+subject.plan
        }).then(function(success){
            successCallback(success);
        }, function(error){
            errorCallback(error);
        });
    }
    
    this.searchSubjects = function(data,successCallback, errorCallback){ 
        $http({ 
            method: 'GET', 
            url: RequestFactory.getURL()+"/subjects/search/"+data
        }).then(function(success){ 
            var data = success.data; 
            // console.log( success ); 
            successCallback(success); 
        }, function(error){ 
            // console.log( error ); 
            errorCallback(error); 
        }); 
    } 


    this.addSubjects = function(subject, successCallback, errorCallback){
        $http({
            method: 'POST',
            url: RequestFactory.getURL()+"/subjects",
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
            url: RequestFactory.getURL()+"/subjects/"+subject.id,
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
            url: RequestFactory.getURL()+"/subjects/"+subject_id+"/status/"+status
        }).then(function (success){
            successCallback(success);
        },function (error){
            errorCallback(error);
        });
    }

    this.deleteSubject = function(subject_id, successCallback, errorCallback){
        $http({
            method: 'DELETE',
            url: RequestFactory.getURL()+"/subjects/"+subject_id
        }).then(function (success){
            successCallback(success);
        },function (error){
            errorCallback(error);
        });
    }
    
    

});