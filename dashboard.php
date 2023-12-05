<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="assets/img/favicon.ico">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js">
    </script>
	<title>Light Bootstrap Dashboard by Creative Tim</title>

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />

    <!-- Bootstrap core CSS     -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Animation library for notifications   -->
    <link href="assets/css/animate.min.css" rel="stylesheet"/>

    <!--  Light Bootstrap Table core CSS    -->
    <link href="assets/css/light-bootstrap-dashboard.css?v=1.4.0" rel="stylesheet"/>


    <!--  CSS for Demo Purpose, don't include it in your project     -->
    <link href="assets/css/demo.css" rel="stylesheet" />


    <!--     Fonts and icons     -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
    <link href="assets/css/pe-icon-7-stroke.css" rel="stylesheet" />

</head>
<body>

<div class="wrapper">
    <div class="sidebar" data-color="purple" data-image="assets/img/sidebar-5.jpg">

    <!--

        Tip 1: you can change the color of the sidebar using: data-color="blue | azure | green | orange | red | purple"
        Tip 2: you can also add an image using data-image tag

    -->
        <?php include('includes/sidebar.php') ?>

    	
    </div>

    <div class="main-panel">
        <nav class="navbar navbar-default navbar-fixed">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">Dashboard</a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-left">
                        <li>
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-dashboard"></i>
								<p class="hidden-lg hidden-md">Dashboard</p>
                            </a>
                        </li>
                        <li class="dropdown">
                              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-globe"></i>
                                    <b class="caret hidden-lg hidden-md"></b>
									<p class="hidden-lg hidden-md">
										5 Notifications
										<b class="caret"></b>
									</p>
                              </a>
                              <ul class="dropdown-menu">
                                <li><a href="#">Notification 1</a></li>
                                <li><a href="#">Notification 2</a></li>
                                <li><a href="#">Notification 3</a></li>
                                <li><a href="#">Notification 4</a></li>
                                <li><a href="#">Another notification</a></li>
                              </ul>
                        </li>
                        <li>
                           <a href="">
                                <i class="fa fa-search"></i>
								<p class="hidden-lg hidden-md">Search</p>
                            </a>
                        </li>
                    </ul>

                    <ul class="nav navbar-nav navbar-right">
                        <li>
                           <a href="">
                               <p>Account</p>
                            </a>
                        </li>
                        <li class="dropdown">
                              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <p>
										Dropdown
										<b class="caret"></b>
									</p>

                              </a>
                              <ul class="dropdown-menu">
                                <li><a href="#">Action</a></li>
                                <li><a href="#">Another action</a></li>
                                <li><a href="#">Something</a></li>
                                <li><a href="#">Another action</a></li>
                                <li><a href="#">Something</a></li>
                                <li class="divider"></li>
                                <li><a href="#">Separated link</a></li>
                              </ul>
                        </li>
                        <li>
                            <a href="#">
                                <p>Log out</p>
                            </a>
                        </li>
						<li class="separator hidden-lg"></li>
                    </ul>
                </div>
            </div>
        </nav>


        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">

                            <div class="header">
                                <h4 class="title">Shippers Statistics</h4>
                                <p class="category">Orders Shipped by every Shipper</p>
                            </div>
                            <div class="content">

                            <canvas id="chartShippers"></canvas>
                            
                            <?php
                                include('config/config.php');
                                include('config/db.php');

                                $query01 = "SELECT shippers.CompanyName, (Count(*)/(SELECT COUNT(*) FROM northwind_appdev.orders) * 100)
                                as Count_Orders FROM northwind_appdev.orders, northwind_appdev.shippers WHERE shippers.ShipperID=orders.ShipVia GROUP BY orders.ShipVia;";

                                $result01 = mysqli_query($conn, $query01);

                                if(mysqli_num_rows($result01) > 0) {
                                    $Count_Orders = array();
                                    $label_piechart = array();
                                    while($row = mysqli_fetch_array($result01)){
                                        $Count_Orders[] = $row['Count_Orders'];
                                        $label_piechart[] = $row['CompanyName'];
                                    }

                                    mysqli_free_result($result01);
                                    mysqli_close($conn);
                                }else{
                                    echo "No records matching your query were found.";
                                }

                            ?>
                            <script>
                                const Count_Orders = <?php echo json_encode($Count_Orders); ?>;
                                const label_piechart = <?php echo json_encode($label_piechart); ?>;
                                const data1 = {
                                    labels: label_piechart,
                                    datasets: [{
                                        label: 'My First Dataset',
                                        data: Count_Orders,
                                        backgroundColor: [
                                            'rgb(166, 255, 255)',
                                            'rgb(166, 227, 165),
                                            'rgb(130, 116, 165)',
                                        ],
                                        hoverOffset: 4
                                    }]
                                };

                                const config = {
                                    type: 'pie',
                                    data: data1,
                                };

                                const chartShippers = new Chart(
                                    document.getElementById('chartShippers'),
                                    config
                                );
                            </script>



                                <!-- <div id="chartPreferences" class="ct-chart ct-perfect-fourth"></div> -->

                                <!-- <div class="footer">
                                    <div class="legend">
                                        <i class="fa fa-circle text-info"></i> Open
                                        <i class="fa fa-circle text-danger"></i> Bounce
                                        <i class="fa fa-circle text-warning"></i> Unsubscribe
                                    </div>
                                    <hr>
                                    <div class="stats">
                                        <i class="fa fa-clock-o"></i> Campaign sent 2 days ago
                                    </div>
                                </div> -->
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Top 3 products</h4>
                                <p class="category">Number of Orders Monthly</p>
                            </div>
                            <div class="content">

                                <canvas id="chartTop3"></canvas>
                                <?php
                                
                                require('config/config.php');
                                require('config/db.php');
                                $query_top3 = "SELECT ProductName FROM northwind_appdev.order_details, northwind_appdev.products
                                WHERE products.ProductID=order_details.ProductID
                                GROUP BY products.ProductID order by count(*) desc, products.ProductName limit 3;";
                                
                                $result_top3 = mysqli_query($conn,$query_top3);
                                $products_top3 = array();
                                while ($row = mysqli_fetch_array($result_top3)){
                                    $products_top3[] = $row['ProductName'];
                                }

                                mysqli_free_result($result_top3);

                                $Top1_Count = array_fill(0,12,0);
                                $Top2_Count = array_fill(0,12,0);
                                $Top3_Count = array_fill(0,12,0);

                                for ($counter=0; $counter<3; $counter++){
                                    $query02 = "SELECT EXTRACT(MONTH FROM o.OrderDate) as Month_1997,
                                    p.ProductName, COUNT(*) as num_order
                                    FROM northwind_appdev.order_details od, northwind_appdev.orders o,
                                    northwind_appdev.products p
                                    WHERE o.orderid = od.orderid and p.productid = od.ProductID and
                                    o.orderdate LIKE '1997%' and
                                    p.ProductName = '" . $products_top3[$counter] .
                                    "' GROUP BY p.ProductName, Month_1997
                                    ORDER BY Month_1997, p.ProductName;";

                                    $result02 = mysqli_query($conn, $query02);
                                    if(mysqli_num_rows($result02) > 0){
                                        while ($row = mysqli_fetch_array($result02)){
                                            if ($counter==0){
                                                $Top1_Count[$row['Month_1997']] = $row['num_order'];
                                            } elseif ($counter==1){
                                                $Top2_Count[$row['Month_1997']] = $row['num_order'];
                                            } else {
                                                $Top3_Count[$row['Month_1997']] = $row['num_order'];
                                            }
                                        }
                                    }
                                }
                                ?>
                                <script>
                                    const Top1_Count = <?php echo json_encode($Top1_Count); ?>;
                                    const Top2_Count = <?php echo json_encode($Top2_Count); ?>;
                                    const Top3_Count = <?php echo json_encode($Top3_Count); ?>;
                                    const label_1 = <?php echo json_encode($products_top3[0]); ?>;
                                    const label_2 = <?php echo json_encode($products_top3[1]); ?>;
                                    const label_3 = <?php echo json_encode($products_top3[2]); ?>;
                                    const data2 = {
                                            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep',
                                            'Oct', 'Nov', 'Dec'],
                                            datasets: [{
                                            label: label_1,
                                            data: Top1_Count,
                                            fill: false,
                                            backgroundColor: 'rgb(255, 99, 71)',
                                            borderColor: 'rgba(255, 99, 71, 0.5)',
                                            tension: 0.1
                                        },
                                        {
                                            label: label_2,
                                            data: Top2_Count,
                                            fill: false,
                                            backgroundColor: 'rgb(191, 64, 64)',
                                            borderColor: 'rgb(191, 255, 0)'
                                            tension: 0.1
                                        },
                                        {
                                            label: label_3,
                                            data: Top3_Count,
                                            fill: false,
                                            backgroundColor: 'rgb(255, 204, 204)',
                                            borderColor: 'rgb(128, 128, 128)',
                                            tension: 0.1
                                        }]
                                    };
                                    // <!-- config block -->
                                    const config2 = {
                                    type: 'line',
                                    data: data2,
                                    };
                                    // <!-- render block -->
                                    const chartTop3 = new Chart(
                                    document.getElementById('chartTop3'),
                                    config2
                                );
                                </script>
                                <!-- <div id="chartHours" class="ct-chart"></div>
                                <div class="footer">
                                    <div class="legend">
                                        <i class="fa fa-circle text-info"></i> Open
                                        <i class="fa fa-circle text-danger"></i> Click
                                        <i class="fa fa-circle text-warning"></i> Click Second Time
                                    </div>
                                    <hr>
                                    <div class="stats">
                                        <i class="fa fa-history"></i> Updated 3 minutes ago
                                    </div>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>



                <div class="row">
                    <div class="col-md-6">
                        <div class="card ">
                            <div class="header">
                                <h4 class="title">2017 Sales</h4>
                                <p class="category">All Meat/Poultry and Seafood Products</p>
                            </div>
                            <div class="content">
                                <canvas id="chartMeatvsSeafood"></canvas>

                                <?php
                                
                                require('config/config.php');
                                require('config/db.php');
                                $query03 = "SELECT EXTRACT(MONTH FROM o.orderdate) as Month_1997,
                                    cat.CategoryName as CategoryName, SUM(od.UnitPrice*od.Quantity*(1-od.Discount))
                                    as sales
                                    FROM northwind_appdev.order_details od, northwind_appdev.orders o, northwind_appdev.products
                                    p, northwind_appdev.categories cat
                                    WHERE o.orderid = od.orderid and p.productid = od.ProductID AND
                                    p.CategoryID=cat.CategoryID and
                                    cat.CategoryName in('Meat/Poultry','Seafood') and o.orderdate LIKE
                                    '1997%'
                                    GROUP BY cat.CategoryName, Month_1997
                                    ORDER BY Month_1997, cat.CategoryName;";
                                $result03 = mysqli_query($conn, $query03);

                                if(mysqli_num_rows($result03) > 0){
                                    $Sales_Meat = array();
                                    $Sales_Seafood = array();
                                    while ($row = mysqli_fetch_array($result03)){
                                        if($row['CategoryName']=='Seafood'){
                                            $Sales_Seafood[] = $row['sales'];
                                        }else{
                                            $Sales_Meat[] = $row['sales'];
                                    }   
                                }

                                mysqli_free_result($result03);
                                mysqli_close($conn);
                                }else{
                                echo "No records matching your query were found.";
                                }
                                
                                
                                ?>

                                <script>
                                    // <!-- setup block -->
                                const Sales_Meat = <?php echo json_encode($Sales_Meat); ?>;
                                const Sales_Seafood = <?php echo json_encode($Sales_Seafood); ?>;
                                const data3 ={
                                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep',
                                        'Oct', 'Nov', 'Dec'],
                                        datasets: [{
                                        label: 'Meat/Poultry',
                                        data: Sales_Meat,
                                        backgroundColor: [
                                            'rgb(224, 194, 255)'
                                        ],
                                        borderColor: [
                                            'rgb(255, 194, 209)'
                                        ],
                                        borderWidth: 1
                                    },
                                    {
                                        label: 'SeaFood',
                                        data: Sales_Seafood,
                                        backgroundColor: [
                                        'rgb(209, 255, 194)'
                                        ],
                                        borderColor: [
                                        'rgb(255, 194, 194)'
                                        ],
                                        borderWidth: 1
                                    }]
                                };
                                // <!-- config block -->
                                const config3 = {
                                    type: 'bar',
                                    data: data3,
                                    options: {
                                        scales: {
                                            y: {
                                                beginAtZero: true
                                            }
                                        }
                                    }
                                };

                                // <!-- render block -->
                                const chartMeatvsSeafood = new Chart(
                                    document.getElementById('chartMeatvsSeafood'),
                                    config3
                                );
                                </script>
                                <!-- <div id="chartActivity" class="ct-chart"></div>

                                <div class="footer">
                                    <div class="legend">
                                        <i class="fa fa-circle text-info"></i> Tesla Model S
                                        <i class="fa fa-circle text-danger"></i> BMW 5 Series
                                    </div>
                                    <hr>
                                    <div class="stats">
                                        <i class="fa fa-check"></i> Data information certified
                                    </div>
                                </div> -->
                            </div>
                        </div>
                    </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Top 5 Ordered Products</h4>
                                <p class="category">Products</p>
                            </div>
                            <div class="content">
                                <canvas id="myChartTopFive"></canvas>
                                <?php
                                require('config/config.php');
                                require('config/db.php');
                                $query04 = "SELECT ProductName, count(*) as order_count FROM
                                    northwind_appdev.order_details, northwind_appdev.products WHERE
                                    products.ProductID=order_details.ProductID GROUP BY products.ProductID order by
                                    order_count desc, products.Productname limit 5;";
                                $result04 = mysqli_query($conn, $query04);

                                if(mysqli_num_rows($result04) > 0){
                                    $order_count = array();
                                    $label_barchart = array();
                                    while ($row = mysqli_fetch_array($result04)){
                                        $order_count[] = $row['order_count'];
                                        $label_barchart[] = $row['ProductName'];
                                    }
                                    mysqli_free_result($result04);
                                    mysqli_close($conn);
                                }else{
                                    echo "No records matching your query were found.";
                                }
                                
                                ?>

                                <script>
                                
                                const label_barchart = <?php echo json_encode($label_barchart); ?>;
                                const order_count = <?php echo json_encode($order_count); ?>;
                                const data4 = {
                                    labels: label_barchart,
                                    datasets: [{
                                        label: 'Number of Orders',
                                        data: order_count,
                                        backgroundColor: [
                                            'rgba(255, 99, 132, 0.2)',
                                            'rgba(54, 162, 235, 0.2)',
                                            'rgba(255, 206, 86, 0.2)',
                                            'rgba(75, 192, 192, 0.2)',
                                            'rgba(153, 102, 255, 0.2)',
                                            'rgba(255, 159, 64, 0.2)'
                                        ],
                                        borderColor: [
                                            'rgba(255, 99, 132, 1)',
                                            'rgba(54, 162, 235, 1)',
                                            'rgba(255, 206, 86, 1)',
                                            'rgba(75, 192, 192, 1)',
                                            'rgba(153, 102, 255, 1)',
                                            'rgba(255, 159, 64, 1)'
                                        ],
                                        borderWidth: 1
                                    }]
                                };
                                // <!-- config block -->
                                const config4 = {
                                    type: 'bar',
                                    data: data4,
                                    options: {
                                        scales: {
                                            y: {
                                                beginAtZero: true
                                            }
                                        }
                                    }
                                };
                                // <!-- render block -->
                                const myChartTopFive = new Chart(
                                    document.getElementById('myChartTopFive'),
                                    config4
                                );

                                </script>
                            </div>
                        </div>
                    </div>
                </div>
                    <div class="col-md-6">
                        <div class="card ">
                            <div class="header">
                                <h4 class="title">Tasks</h4>
                                <p class="category">Backend development</p>
                            </div>
                            <div class="content">
                                <div class="table-full-width">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td>
													<div class="checkbox">
						  							  	<input id="checkbox1" type="checkbox">
						  							  	<label for="checkbox1"></label>
					  						  		</div>
                                                </td>
                                                <td>Sign contract for "What are conference organizers afraid of?"</td>
                                                <td class="td-actions text-right">
                                                    <button type="button" rel="tooltip" title="Edit Task" class="btn btn-info btn-simple btn-xs">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                    <button type="button" rel="tooltip" title="Remove" class="btn btn-danger btn-simple btn-xs">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
													<div class="checkbox">
						  							  	<input id="checkbox2" type="checkbox" checked>
						  							  	<label for="checkbox2"></label>
					  						  		</div>
                                                </td>
                                                <td>Lines From Great Russian Literature? Or E-mails From My Boss?</td>
                                                <td class="td-actions text-right">
                                                    <button type="button" rel="tooltip" title="Edit Task" class="btn btn-info btn-simple btn-xs">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                    <button type="button" rel="tooltip" title="Remove" class="btn btn-danger btn-simple btn-xs">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
													<div class="checkbox">
						  							  	<input id="checkbox3" type="checkbox">
						  							  	<label for="checkbox3"></label>
					  						  		</div>
                                                </td>
                                                <td>Flooded: One year later, assessing what was lost and what was found when a ravaging rain swept through metro Detroit
												</td>
                                                <td class="td-actions text-right">
                                                    <button type="button" rel="tooltip" title="Edit Task" class="btn btn-info btn-simple btn-xs">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                    <button type="button" rel="tooltip" title="Remove" class="btn btn-danger btn-simple btn-xs">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
													<div class="checkbox">
						  							  	<input id="checkbox4" type="checkbox" checked>
						  							  	<label for="checkbox4"></label>
					  						  		</div>
                                                </td>
                                                <td>Create 4 Invisible User Experiences you Never Knew About</td>
                                                <td class="td-actions text-right">
                                                    <button type="button" rel="tooltip" title="Edit Task" class="btn btn-info btn-simple btn-xs">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                    <button type="button" rel="tooltip" title="Remove" class="btn btn-danger btn-simple btn-xs">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
													<div class="checkbox">
						  							  	<input id="checkbox5" type="checkbox">
						  							  	<label for="checkbox5"></label>
					  						  		</div>
                                                </td>
                                                <td>Read "Following makes Medium better"</td>
                                                <td class="td-actions text-right">
                                                    <button type="button" rel="tooltip" title="Edit Task" class="btn btn-info btn-simple btn-xs">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                    <button type="button" rel="tooltip" title="Remove" class="btn btn-danger btn-simple btn-xs">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
													<div class="checkbox">
						  							  	<input id="checkbox6" type="checkbox" checked>
						  							  	<label for="checkbox6"></label>
					  						  		</div>
                                                </td>
                                                <td>Unfollow 5 enemies from twitter</td>
                                                <td class="td-actions text-right">
                                                    <button type="button" rel="tooltip" title="Edit Task" class="btn btn-info btn-simple btn-xs">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                    <button type="button" rel="tooltip" title="Remove" class="btn btn-danger btn-simple btn-xs">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="footer">
                                    <hr>
                                    <div class="stats">
                                        <i class="fa fa-history"></i> Updated 3 minutes ago
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="footer">
            <div class="container-fluid">
                <nav class="pull-left">
                    <ul>
                        <li>
                            <a href="#">
                                Home
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                Company
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                Portfolio
                            </a>
                        </li>
                        <li>
                            <a href="#">
                               Blog
                            </a>
                        </li>
                    </ul>
                </nav>
                <p class="copyright pull-right">
                    &copy; <script>document.write(new Date().getFullYear())</script> <a href="http://www.creative-tim.com">Creative Tim</a>, made with love for a better web
                </p>
            </div>
        </footer>
    </div>
</div>
</body>
    <!--   Core JS Files   -->
    <script src="assets/js/jquery.3.2.1.min.js" type="text/javascript"></script>
	<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
	<!--  Charts Plugin -->
	<script src="assets/js/chartist.min.js"></script>
    <!--  Notifications Plugin    -->
    <script src="assets/js/bootstrap-notify.js"></script>
    <!--  Google Maps Plugin    -->
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
    <!-- Light Bootstrap Table Core javascript and methods for Demo purpose -->
	<script src="assets/js/light-bootstrap-dashboard.js?v=1.4.0"></script>
	<!-- Light Bootstrap Table DEMO methods, don't include it in your project! -->
	<script src="assets/js/demo.js"></script>
	<script type="text/javascript">
    	$(document).ready(function(){
        	demo.initChartist();
        	$.notify({
            	icon: 'pe-7s-gift',
            	message: "Welcome to <b>Light Bootstrap Dashboard</b> - a beautiful freebie for every web developer."
            },{
                type: 'info',
                timer: 4000
            });
    	});
	</script>
</html>