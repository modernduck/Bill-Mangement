angular.module("billing.app", ['ngRoute', 'routeStyles', 'billing.editor', 'billing.manager'])
	.config(['$routeProvider', 
		function ($routeProvider) {
			$routeProvider
				.when('/', {
					templateUrl : 'partials/home.html',
					controller : 'MainCtrl'
				})
				.when('/receipt', {
					templateUrl : 'partials/receipt.html',
					controller : 'ReceiptCtrl',
					css : ['css/receipt.css']
				})
				.when('/invoice', {
					templateUrl : 'partials/invoice.html',
					controller : 'InvoiceCtrl'
				})


		}])

angular.module("billing.filter", [])
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


	

angular.module("billing.model", ["billing.filter"])
	.factory("Document", function ($filter) {

		return {
			"RECEIPT" : 1,
			"INVOICE" : 2,
			"currency" : "B",
			"query" : function ()
			{

			},
			"get" : function()
			{

			},
			"getSample" : function(type)
			{
				return {
					"id" : 1,
					"currency" : this.currency,
					"type" : type,
					"name" : "สมภพ กุละปาลานนท์",
					"no" : "00123",
					"our" : {
						"name" : "บริษัท ปิกนี่ จำกัด(สาขาใหญ่)",
						"phone" : "(66)2-693-7582",
						"tax" : "0105555017889",
						"email" : "sompop@picnii.me",
						"address" : "1010 ถนน สุทธิสาร\n เขต ดินแดง แขวง ดินแดง \nกรุงเทพ 10400",
						"logo" : "http://placehold.it/200x100",
					},
					"customer" :
					{
						"name" : "บริษัท เลเวลอัพ สตูดิโอ จำกัด ",
						"company" : "บริษัท เลเวลอัพ สตูดิโอ จำกัด ",
						"address" : "737/4 ซอยพิบูลอุปถัมภ์ แขวงสามเสนนอก เขตห้วยขวาง กรุงเทพมหานคร 10310",
						"tax" : "0105552037854"
					},
					"recipient": "สมภพ กุละปาลานนท์",
					"date" : "December 20, 2015",
					"paidAmount" : 500.00,
					"terms" : "NET 30 Days. Finance Charge of 1.5% will be made on unpaid balances after 30 days.",
					"orders" : [
						{ id : 1, name : "Web Update", description : "Work yearh", cost:50, quantity:3}
					],
					"getLastId" : function()
					{
						return this.orders[ this.orders.length - 1 ].id
					},
					"new" : function()
					{
						var new_id = this.getLastId() + 1;
						var new_order = {id : new_id, cost:0, quantity:0 }
						this.orders.push(new_order)
					},
					"delete" : function( order_item )
					{
						_.remove(this.orders, {id: order_item.id})
					},


				}
			}

		}
	});


angular.module("billing.manager", ["billing.model", "billing.filter"])
	.controller("MainCtrl", function ($scope) {
		$scope.invoices = [];
		$scope.receipts = [];
	})

angular.module("billing.editor", ["billing.model", "billing.filter"])
	.controller("ReceiptCtrl", function ($scope, Document){
		$scope.receipt = Document.getSample(Document.RECEIPT);
		$scope.test = "ใบเสร็จรับเงิน/ใบกำกับภาษี";
		$scope.options = {
			showTerms:true,
			showLogo:true
		}
		$scope.newRow = function()
		{
			$scope.receipt.new();
		}
		$scope.deleteRow = function( order_item )
		{
			$scope.receipt.delete( order_item )
		}

		$scope.deleteLogo = function( )
		{
			$scope.options.showLogo = false;
		}
	})
	.controller("InvoiceCtrl", function ($scope, Document){
		$scope.invoice = Document.getSample(Document.INVOICE);
		$scope.test = "Invoice";
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