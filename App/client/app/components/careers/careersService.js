
app.service('CareersService', function($http){

    // this.data = [];
    
    this.getCareers = function(callback){
        $http({
            method: 'GET',
            url: "http://localhost:81/www/AsesoriasParWeb3/App/server/index.php/careers"
        }).then(function(success){
            var data = success.data;
            console.log( data );
            callback(data);

        }, function(error){
            console.log( error );
            callback(data);
        });
    }

    this.addCareer = function(callback, career){
        $http({
            method: 'POST',
            url: "http://localhost:81/www/AsesoriasParWeb3/App/server/index.php/careers",
            data: {
                name: career.name,
                short_name: career.short_name
            }
        }).then(function(success){
            console.log( success );
            callback(success) 
        }, function(error){
            console.log( error );
            callback(error)
        });
    }

    this.updateCareer = function(callback, career){
        $http({
            method: 'PUT',
            url: "http://localhost:81/www/AsesoriasParWeb3/App/server/index.php/careers/"+career.id,
            data: {
                name: career.name,
                short_name: career.short_name
            }
        }).then(function (success){
            console.log( success );
            callback(success);
        },function (error){
            console.log( error );
            callback(error);
        });
    }

    this.updateStatusCareer = function(callback, career){
        $http({
            method: 'PATCH',
            url: "http://localhost:81/www/AsesoriasParWeb3/App/server/index.php/careers/"+career.id+"/status/"+career.status
        }).then(function (success){
            console.log( success );
            callback(success);
        },function (error){
            console.log( error );
            callback(error);
        });
    }
    
    this.deleteCareer = function(callback, career_id){
        $http({
            method: 'DELETE',
            url: "http://localhost:81/www/AsesoriasParWeb3/App/server/index.php/careers/"+career_id
        }).then(function (success){
            // console.log( response.data.message );
            callback(success);
        },function (error){
            // console.log( response.data.message );
            callback(error);
        });
    }
});