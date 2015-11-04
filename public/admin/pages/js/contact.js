function contactController($scope, $http) {
	$scope.$parent.page = true;
	$scope.$parent.homepage = true;
	$scope.$parent.homepreview = true;
    $scope.$parent.hidepreviews = true;
	$scope.$parent.menu = [];

	$http.get('json/contact.json').success(function(json) {
		if (json.err == 0) {
			$scope.message = json.data.message;
		}
	});
}