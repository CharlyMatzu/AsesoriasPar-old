app.service('UsersService', function($http){

    // this.data = [];
    
    this.getAll = function(){
        $http({
            method: 'GET',
            url: "http://api.asesoriaspar.com/index.php/users"
        }).then(function(success){
            
            var data = success.data;
            console.log( data );
            return data;

        }, function(error){
            console.log( error );
        });
    }

    this.addUser = function(user){
        $http({
            method: 'POST',
            url: "http://api.asesoriaspar.com/index.php/users",
            data: { user }
        }).then(function(success){
            console.log( success );    
        }, function(error){
            console.log( error );
        });
    }

    this.updateUser = function(user){
        $http({
            method: 'PUT',
            url: "http://api.asesoriaspar.com/index.php/users"+item.user_id
        }).then(function (response){
            //$scope.loading = false;
            //$scope.users = response.data.data;
            console.log( response.data.message );
        },function (response){
            //$scope.loading = false;
            console.log( response.data.message );
        });
    }
    
    this.deleteUser = function(user){
        $http({
            method: 'DELETE',
            url: "http://api.asesoriaspar.com/index.php/users"+item.user_id
        }).then(function (response){
            //$scope.loading = false;
            //$scope.users = response.data.data;
            console.log( response.data.message );
        },function (response){
            //$scope.loading = false;
            console.log( response.data.message );
        });
    }
    
    

});