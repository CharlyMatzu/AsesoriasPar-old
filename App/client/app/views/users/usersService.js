app.service('UsersService', function($http){

    // this.data = [];
    
    this.getUsers = function(callback){
        $http({
            method: 'GET',
            url: "http://api.asesoriaspar.com/index.php/users"
        }).then(function(success){
            
            var data = success.data;
            console.log( data );
            callback(data);

        }, function(error){
            console.log( error );
            callback(data);
        });
    }

    this.addUser = function(callback, user){
        $http({
            method: 'POST',
            url: "http://api.asesoriaspar.com/index.php/users",
            data: {
                email: user.email,
                password: user.pass,
                role: user.role
            }
        }).then(function(success){
            console.log( success );
            callback(success) 
        }, function(error){
            console.log( error );
            callback(error)
        });
    }

    // this.updateUser = function(callback, user){
    //     $http({
    //         method: 'PUT',
    //         url: "http://api.asesoriaspar.com/index.php/users"+item.user_id
    //     }).then(function (response){
    //         console.log( response.data.message );
    //         callback(data);
    //     },function (response){
    //         console.log( response.data.message );
    //         callback(data);
    //     });
    // }
    
    this.deleteUser = function(callback, user_id){
        $http({
            method: 'DELETE',
            url: "http://api.asesoriaspar.com/index.php/users/"+user_id
        }).then(function (success){
            // console.log( response.data.message );
            callback(success);
        },function (error){
            // console.log( response.data.message );
            callback(error);
        });
    }
    
    

});