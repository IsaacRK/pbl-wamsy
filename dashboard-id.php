<?php

require 'backend/conn.php';
require 'backend/usersession.php';

?>
<!DOCTYPE html>
<html>

<head>
	<title>Dashboard</title>
	<?php include"layout/header.php"?>
	
</head>

<body>

<?php
	include"layout/sidebar-id.php";
?>

<div class="content">
<div class="container mr-0">

	<div class="p-1">
		<div class="mb-3">
			<h1>Halaman Beranda</h1>
		</div>
	</div>

	<div class="row">
		<div class="col-sm">
			<div class="card">
			<div class="card-body">
			
				<div class="dropdown">
				  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Arduino
				  </button>
				  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
					<a class="dropdown-item" href="#">Action</a>
					<a class="dropdown-item" href="#">Another action</a>
					<a class="dropdown-item" href="#">Something else here</a>
				  </div>
				</div>
			
				</br>
				
				<div class="card">
					<div class="card-body"  style="background-color:#2879ff73;">
						<div class="container">
						<div id="mapping" class="carousel slide" data-bs-ride="carousel">
							<!-- Carousel indicators -->
							<ol class="carousel-indicators">
								<?php
									$maxStorageIdQuery = "select max(storage_id) from penyimpanan";
									$maxStorageIdRun = mysqli_query($servConnQuery,$maxStorageIdQuery);
									$maxStorageIdFetch = mysqli_fetch_array($maxStorageIdRun);
									$maxStorageId = $maxStorageIdFetch[0];
									$maxStorageIdRounded = round($maxStorageId/10,0,PHP_ROUND_HALF_DOWN);
									
									for($i=0;$i<$maxStorageIdRounded;$i++){
										if($i==0){
											echo'<li data-bs-target="#mapping" data-bs-slide-to="'.$i.'" class="active"></li>';
										}else{
											echo'<li data-bs-target="#mapping" data-bs-slide-to="'.$i.'"></li>';
										}
									}
								?>
							</ol>
							
							<!-- Wrapper for carousel items -->
							<div class="carousel-inner">
								<?php
									function filter_even($var){
										return($var = $var % 2);
									}
									
									function boxColorFunc($fetch){
										if($fetch==null){
											$col='color-tertiary';
										}else{
											$col='color-primary';
										}
										return($col);
									}
									
									function stringEcho($xx,$xxx){
										$string = '
										<div class="col p-1 d-flex justify-content-center">
											<div class="shadow-sm border rounded rak-box '.$xx.'">
												'.$xxx.'
											</div>
										</div>';
										return ($string);
									}
									
									for($a=0;$a<$maxStorageIdRounded;$a++){
										if($a==0){
											echo'
											<div class="carousel-item active">
												<div class="row">';
										}else{
											echo'
											<div class="carousel-item">
												<div class="row">';
										}
										
										$mappingOffsetQuery='';
										if($a==0){
											$mappingOffsetQuery='';
										}else{
											$z = $a * 10;
											$mappingOffsetQuery='offset '.$z;
										}
										
										$mappingQuery = "select * from penyimpanan limit 10 ".$mappingOffsetQuery;
										$mappingRun = mysqli_query($servConnQuery, $mappingQuery);
										if(mysqli_num_rows($mappingRun)>0){
											$stringBoxTop='';
											$stringBoxBottom='';
											$box='';
											while ($mappingFetch = mysqli_fetch_assoc($mappingRun)){
												if(filter_even($mappingFetch['storage_id'])==1){
													$box = boxColorFunc($mappingFetch['stock_id']);
													$stringBox = stringEcho($box,$mappingFetch['storage_id']);
													$stringBoxTop=$stringBoxTop.$stringBox;
												}else{
													$box = boxColorFunc($mappingFetch['stock_id']);
													$stringBox = stringEcho($box,$mappingFetch['storage_id']);
													$stringBoxBottom=$stringBoxBottom.$stringBox;
												}
											}
											echo $stringBoxTop.'<div class="w-100"></div>'.$stringBoxBottom;
										}
										
										echo'</div></div>';
									}
								?>
							</div>
							
							<!-- Carousel controls -->
							<a class="carousel-control-prev" href="#mapping" data-bs-slide="prev">
								<span class="carousel-control-prev-icon"></span>
							</a>
							<a class="carousel-control-next" href="#mapping" data-bs-slide="next">
								<span class="carousel-control-next-icon"></span>
							</a>
							
						</div>
						</div>
						
						<div class="row">
						<div class="container mx-5">
						<div class="row">
							<div class="col">
								<div class="row mt-2">
								<div class="col pr-0 d-flex justify-content-center">
									<div class="shadow-sm border rounded rak-box color-tertiary m-0"></div>
								</div>
								<div class="col pl-0">
									<p class="align-middle m-0 pt-2">Free Space</p>
								</div>
								</div>
							</div>
							<div class="col">
								<div class="row mt-2">
								<div class="col pr-0 d-flex justify-content-center">
									<div class="shadow-sm border rounded rak-box color-primary m-0"></div>
								</div>
								<div class="col pl-0">
									<p class="align-middle m-0 pt-2">Full</p>
								</div>
								</div>
							</div>
						</div>
						</div>
						</div>
						
					</div>
				</div>
				
				<h4 class="card-title">Daftar Komponen</h4>
				
				<table class="table table-striped">
					<thead>
					<tr>
						<th scope="col">Nama Komponen</th>
						<th scope="col">Stok</th>
					</tr>
					</thead>
					<tbody>
						<?php
							$query = "select * from stock order by stock_id desc limit 5";
							$run = mysqli_query($servConnQuery, $query);
							
							if(mysqli_num_rows($run)>0){
								while($fetch = mysqli_fetch_assoc($run)){
									echo"
										<tr>
											<td>".$fetch['stock_name']."</td>
											<td>".$fetch['amount']."</td>
										</tr>
									";
								}
							}
						?>
					</tbody>
				</table>
			
			</div>
			</div>
		</div>
		
		<div class="col-sm">
			<div class="card">
			<div class="card-body">
			
				<div class="dropdown">
				  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Arduino
				  </button>
				  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
					<a class="dropdown-item" href="#">Action</a>
					<a class="dropdown-item" href="#">Another action</a>
					<a class="dropdown-item" href="#">Something else here</a>
				  </div>
				</div>
			
				</br>
			
				<div class="card">
					<div class="card-body" style="height:250px;">
						<canvas id="chartDisplay" width="" height=""></canvas>
					</div>
				</div>
				
				<h4 class="card-title">Jejak Aktifitas</h4>
				
				<div class="table-responsive">
				<table class="table table-striped">
					<thead>
					<tr>
						<th scope="col">Nama Komponen</th>
						<th scope="col">Tanggal</th>
						<th scope="col">Pemohon</th>
						<th scope="col">Kuantitas</th>
					</tr>
					</thead>
					<tbody>
					<tr>
						<td>arduino nano</td>
						<td>13/09</td>
						<td>anon</td>
						<td>5</td>
					</tr>
					<tr>
						<td>arduino nano</td>
						<td>13/09</td>
						<td>anon</td>
						<td>5</td>
					</tr>
					<tr>
						<td>arduino nano</td>
						<td>13/09</td>
						<td>anon</td>
						<td>5</td>
					</tr>
					</tbody>
				</table>
				</div>
			
			</div>
			</div>
		</div>
	</div>
</div>
</div>

<?php
	include"layout/js.php";
?>

<script>
	var chartDisplay = document.getElementById("chartDisplay");

	var speedData = {
	  labels: ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9"],
	  datasets: [{
		label: "Catatan Aktifitas",
		backgroundColor: 'rgb(16, 100, 174)',
		borderColor: 'rgb(16, 100, 174)',
		data: [0,2,2,3,3,4,2,1,3,5],
	  }]
	};

	var chartOptions = {
	  legend: {
		display: true,
		position: 'top',
		labels: {
		  boxWidth: 10,
		  fontColor: 'green'
		}
	  }
	};

	var lineChart = new Chart(chartDisplay, {
	  type: 'line',
	  data: speedData,
	  options: chartOptions
	});
</script>

</body>
</html>