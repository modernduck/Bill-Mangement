angular.module("documentData", ['ngResource'])
	.factory('Docs', ['$resource',
	  function($resource){
	    return $resource('../service/:action/:id', {}, {
	      savedocs: {method:'POST', params:{action:'savedocs'}, isArray:false}
	    });
	  }]);

angular.module("invoice.filter", [])
	.filter("subtotal", function () {
		return function (invoice){

			var sum = 0;
			var orders = invoice.orders;
			for(var i =0; i < orders.length ;i ++)
				sum += orders[i].cost * orders[i].quantity
			return sum;
		}
	})
	.filter("balance", function ($filter) {
		return function (invoice)
		{
			var subtotal = $filter("subtotal")(invoice);
			return subtotal - invoice.paidAmount;
		}
	})


	

angular.module("invoice.model", ["invoice.filter"])
	.factory("Invoice", function ($filter) {

		return {
			currency : "B",
			query : function ()
			{

			},
			get : function()
			{

			},
			getSample : function(preset)
			{
				preset.getLastId = function()
				{
					return this.orders[ this.orders.length - 1 ].id
				};
				preset.new = function()
				{
					var new_id = this.getLastId() + 1;
					var new_order = {id : new_id, cost:0, quantity:0 }
					this.orders.push(new_order)
				};
				preset.delete = function( order_item )
				{
					_.remove(this.orders, {id: order_item.id})
				};
				return preset
					


				
			}

		}
	});

angular.module("invoice.editor", ["invoice.model", "invoice.filter", "documentData"])
	.controller("EditorCtrl", function ($scope, Invoice, Docs, $filter){
		$scope.preset = JSON.parse(document.getElementById("data").innerHTML);
		$scope.invoice = Invoice.getSample($scope.preset);
		$scope.test = "ใบเสร็จรับเงิน/ใบกำกับภาษี";
		$scope.template_data = JSON.parse(document.getElementById("template_data").innerHTML);
		$scope.saveDocument = function()
		{
			console.log('gonna save')
			$scope.invoice.total = $filter('subtotal')($scope.invoice);
			console.log({data:$scope.invoice, id:$scope.template_data.id})
			Docs.savedocs({data:$scope.invoice, id:$scope.template_data.id}, function(data){
				console.log('result')
				console.log(data)
				window.location = data.uri;
			});
		}

		$scope.options = {
			showTerms:true,
			showLogo:true
		}
		$scope.newRow = function()
		{
			$scope.invoice.new();
		}
		$scope.deleteRow = function( order_item )
		{
			$scope.invoice.delete( order_item )
		}

		$scope.deleteLogo = function( )
		{
			$scope.options.showLogo = false;
		}

	});