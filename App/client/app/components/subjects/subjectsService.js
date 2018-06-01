app.service('SubjectsService', function($http){

    
    this.getSubjects = function(successCallback, errorCallback){
        $http({
            method: 'GET',
            url: "http://asesoriaspar.ronintopics.com/index.php/subjects"
        }).then(function(success){
            var data = success.data;
            console.log( success );
            successCallback(success);
        }, function(error){
            console.log( error );
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
                semester:subject.semester ,
                plan: subject.plan_id,
                career: subject.career_id
            }
        }).then(function(success){
            console.log( success );
            successCallback(success) 
        }, function(error){
            console.log( error );
            errorCallback(error)
        });
    }

    this.updateSubject = function(subject_id, successCallback, errorCallback){
        $http({
            method: 'PUT',
            url: "http://asesoriaspar.ronintopics.com/index.php/subjects/"+subject_id,
            data: {
                email: user.email,
                password: user.pass,
                role: user.role
            }
        }).then(function(success){
            console.log( success );
            successCallback(success) 
        }, function(error){
            console.log( error );
            errorCallback(error)
        });
    }
    
    this.deleteSubject = function(subject_id, successCallback, errorCallback){
        $http({
            method: 'DELETE',
            url: "http://asesoriaspar.ronintopics.com/index.php/subjects/"+subject_id
        }).then(function (success){
            // console.log( response.data.message );
            successCallback(success);
        },function (error){
            // console.log( response.data.message );
            errorCallback(error);
        });
    }
    
    

});