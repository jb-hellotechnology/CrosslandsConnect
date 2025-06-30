<?php
include('secrets.php');
include('layouts/header.php');
if(!$session){
	header('/');
	exit();
}
?>
	<main>
		<article>
			<h1>Heading 1</h1>
			<h2>Heading 2</h2>
			<h3>Heading 3</h3>
			<h4>Heading 4</h4>
			<p>Lorem ipsum dolor sit amet, <a href="#">consectetur adipiscing</a> elit. Phasellus suscipit pulvinar nisl, eget sodales lorem lacinia id. Aenean id diam at ligula iaculis fermentum et quis nisi. Suspendisse eu congue risus, gravida posuere lacus. Donec sit amet consequat nulla. Integer eu pellentesque elit.</p>
			<h2>Layout Options</h2>
			<h3>Tabs</h3>
			<div class="tabs-container">
				<ul class="tabs">
					<li class="active" data-tab="tab-1">Tab 1</li>
					<li data-tab="tab-2">Tab 2</li>
					<li data-tab="tab-3">Tab 3</li>
				</ul>
				<div class="tab active tab-1">
					<p>Tab 1. Lorem ipsum dolor sit amet, <a href="#">consectetur adipiscing</a> elit. Phasellus suscipit pulvinar nisl, eget sodales lorem lacinia id. Aenean id diam at ligula iaculis fermentum et quis nisi. Suspendisse eu congue risus, gravida posuere lacus. Donec sit amet consequat nulla. Integer eu pellentesque elit.</p>
				</div>
				<div class="tab tab-2">
					<p>Tab 2. Lorem ipsum dolor sit amet, <a href="#">consectetur adipiscing</a> elit. Phasellus suscipit pulvinar nisl, eget sodales lorem lacinia id. Aenean id diam at ligula iaculis fermentum et quis nisi. Suspendisse eu congue risus, gravida posuere lacus. Donec sit amet consequat nulla. Integer eu pellentesque elit.</p>
				</div>
				<div class="tab tab-3">
					<p>Tab 3. Lorem ipsum dolor sit amet, <a href="#">consectetur adipiscing</a> elit. Phasellus suscipit pulvinar nisl, eget sodales lorem lacinia id. Aenean id diam at ligula iaculis fermentum et quis nisi. Suspendisse eu congue risus, gravida posuere lacus. Donec sit amet consequat nulla. Integer eu pellentesque elit.</p>
				</div>
			</div>
			<h3>Accordion</h3>
			<div class="accordion">
				<h3><span>&plus;</span> Heading</h3>
				<div>
					<p>Lorem ipsum dolor sit amet, <a href="#">consectetur adipiscing</a> elit. Phasellus suscipit pulvinar nisl, eget sodales lorem lacinia id. Aenean id diam at ligula iaculis fermentum et quis nisi. Suspendisse eu congue risus, gravida posuere lacus. Donec sit amet consequat nulla. Integer eu pellentesque elit.</p>
				</div>
				<h3><span>&plus;</span> Heading</h3>
				<div>
					<p>Lorem ipsum dolor sit amet, <a href="#">consectetur adipiscing</a> elit. Phasellus suscipit pulvinar nisl, eget sodales lorem lacinia id. Aenean id diam at ligula iaculis fermentum et quis nisi. Suspendisse eu congue risus, gravida posuere lacus. Donec sit amet consequat nulla. Integer eu pellentesque elit.</p>
				</div>
				<h3><span>&plus;</span> Heading</h3>
				<div>
					<p>Lorem ipsum dolor sit amet, <a href="#">consectetur adipiscing</a> elit. Phasellus suscipit pulvinar nisl, eget sodales lorem lacinia id. Aenean id diam at ligula iaculis fermentum et quis nisi. Suspendisse eu congue risus, gravida posuere lacus. Donec sit amet consequat nulla. Integer eu pellentesque elit.</p>
				</div>
			</div>
			<h3>Table</h3>
			<table>
				 <thead>
					<tr>
						<th>Country</th>
						<th>Mean temperature change (°C)</th>
					</tr>
				 </thead>
				<tbody>
					<tr>
						 <th>United Kingdom</th>
						<td>1.912</td>
					 </tr>
					 <tr>
						 <th>Afghanistan</th>
						<td>2.154</td>
					</tr>
					<tr>
						 <th>Australia</th>
						<td>0.681</td>
					</tr>
					<tr>
						 <th>Kenya</th>
						<td>1.162</td>
					</tr>
					<tr>
						 <th>Honduras</th>
						<td>0.945</td>
					</tr>
					<tr>
						 <th>Canada</th>
						<td>1.284</td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<th>Global average</th>
						<td>1.4</td>
					</tr>
				</tfoot>
			</table>
			<h3>Map</h3>
			<div id="map"></div>
			<script>
				// initialize the map on the "map" div with a given center and zoom
				var map = L.map('map', {
					center: [51.505, -0.09],
					zoom: 13
				});
				L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
					maxZoom: 19,
					attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
				}).addTo(map);
				var marker = L.marker([51.5, -0.09]).addTo(map);
			</script>
			<h3>Cards</h3>
			<ul class="cards">
				<li class="card">
					<img src="https://crosslands.training/crosslands/wp-content/uploads/2023/06/denys-nevozhai-UNv2lxq8Rmo-unsplash-scaled-1920x1080.jpg" alt="Image" />
					<p>Phasellus suscipit pulvinar nisl, eget sodales lorem lacinia id. Aenean id diam at ligula iaculis fermentum et quis nisi. Suspendisse eu congue risus, gravida posuere lacus. Donec sit amet consequat nulla. Integer eu pellentesque elit.</p>
					<a class="button" href="#">Button</a>
				</li>
				<li class="card">
					<img src="https://crosslands.training/crosslands/wp-content/uploads/2023/06/denys-nevozhai-UNv2lxq8Rmo-unsplash-scaled-1920x1080.jpg" alt="Image" />
					<p>Phasellus suscipit pulvinar nisl, eget sodales lorem lacinia id. Aenean id diam at ligula iaculis fermentum et quis nisi. Suspendisse eu congue risus, gravida posuere lacus. Donec sit amet consequat nulla. Integer eu pellentesque elit.</p>
					<a class="button" href="#">Button</a>
				</li>
			</ul>
		</article>
	</main>
	<aside>
		
	</aside>
<?php include('layouts/footer.php'); ?>