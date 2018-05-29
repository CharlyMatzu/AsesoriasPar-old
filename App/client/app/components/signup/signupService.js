app.service('SignupService', function($http){

    this.signup = function(data){
        $http({
            method: 'POST',
            url: "http://api.asesoriaspar.com/index.php/users/student",
            data: data
        }).then( function(success){
            
            console.log( success );

        }, function(error){
            
            console.log( error );

        });
    }

});