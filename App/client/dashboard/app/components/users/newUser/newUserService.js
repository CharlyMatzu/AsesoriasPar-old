app.service('NewUserService', function($http){


    // this.getRoles = function(successCallback, errorCallback){
    //     $http({
    //         method: 'POST',
    //         url: "http://asesoriaspar.ronintopics.com/index.php/",
    //         data: {
    //             email: user.email,
    //             password: user.pass,
    //             role: user.role
    //         }
    //     }).then(function(success){
    //         successCallback(success) 
    //     }, function(error){
    //         errorCallback(error)
    //     });
    // }


    this.addUser = function(user, successCallback, errorCallback){
        $http({
            method: 'POST',
            url: "http://asesoriaspar.ronintopics.com/index.php/users",
            data: {
                email: user.email,
                password: user.pass,
                role: user.role
            }
        }).then(function(success){
            successCallback(success) 
        }, function(error){
            errorCallback(error)
        });
    }
    

});