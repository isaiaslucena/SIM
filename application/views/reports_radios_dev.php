<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<html ng-app="myApp">
	<head>
		<title>AngularJS GET request with PHP</title>
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
		<script src="https://code.angularjs.org/1.6.1/angular.min.js"></script>
		<script src="<?php echo base_url('assets/jquery/jquery-3.1.1.min.js');?>"></script>
	</head>

	<body>
		<br>
		<div class="row">
			<div class="container">
				<h1>Angular $http GET Ajax Call</h1>
				<div ng-controller="dbCtrl">
					<input type="text" ng-model="searchFilter" class="form-control">
						<table class="table table-hover">
							<thead>
								<tr>
									<th>Radio</th>
									<th>Date</th>
									<th>Date Added</th>
								</tr>
							</thead>
							<tbody>
								<tr ng-repeat="radios in data | filter:searchFilter">
									<td>{{ radios.radio }}</td>
									<td>{{ radios.timestamp }}</td>
									<td>{{ radios.timestamp_add }}</td>
								</tr>
							</tbody>
						</table>
				</div>
			</div>
		</div>
	</body>

	<script>
		function loadcontent() {
		var app = angular.module('myApp', [])
			app.controller('dbCtrl', function ($scope, $http) {
				$http.get("api_radios").then(function(res,status,xhr) {
					//console.log(res.data);
					$scope.data = res.data;
				})
			})
		}

		loadcontent();
		setInterval(function(){
			loadcontent();
		},5000);
	</script>

</html>