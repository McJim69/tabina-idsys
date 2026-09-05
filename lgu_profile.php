<?php
require("connect.php");
include("header.php");
?>

<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<link href="style/lgu-profile.css" rel="stylesheet" type="text/css"/>

<div class="container py-4" style="margin-top: 10px;">

	<!-- Top Navigation Breadcrumb -->
	<nav aria-label="breadcrumb" class="mb-3">
		<ol class="breadcrumb bg-white shadow-sm rounded-pill px-4 py-2 border">
			<li class="breadcrumb-item"><a href="index.php" class="text-primary font-weight-semibold"><i class="fas fa-home mr-1"></i>Dashboard</a></li>
			<li class="breadcrumb-item"><a href="public_home.php" class="text-primary font-weight-semibold">Public Portal</a></li>
		</ol>
	</nav>

	<!-- Main LGU Header Hero Card -->
	<div class="profile-hero-card p-4 p-md-5 mb-4">
		<div class="row align-items-center">
			<div class="col-lg-8">
				<div class="d-flex align-items-center mb-3">
					<img src="images/SEAL.png" height="70" class="mr-3 drop-shadow" alt="Tabina Seal">
					<div>
						<span class="badge badge-pill badge-primary px-3 py-1 text-uppercase font-weight-bold" style="background: rgba(59, 130, 246, 0.25); color: #93c5fd;">
							DTI - CMCI
						</span>
						<h2 class="font-weight-bold text-white mb-0 mt-1">LGU Tabina</h2>
						<p class="text-white-50 mb-0">Zamboanga del Sur</p>
					</div>
				</div>

				<div class="row mt-4 pt-2 border-top border-white-50">
					<div class="col-sm-4 mb-2">
						<small class="text-white-50 d-block text-uppercase font-weight-bold" style="font-size: 11px;">Municipal Classification</small>
						<span class="h6 font-weight-bold text-white">4th Class Municipality</span>
					</div>
					<div class="col-sm-4 mb-2">
						<small class="text-white-50 d-block text-uppercase font-weight-bold" style="font-size: 11px;">Municipal Mayor</small>
						<span class="h6 font-weight-bold text-white">Hon. Juhaine A. Malaco</span>
					</div>
					<div class="col-sm-4 mb-2">
						<small class="text-white-50 d-block text-uppercase font-weight-bold" style="font-size: 11px;">Census Population</small>
						<span class="h6 font-weight-bold text-white">25,734 Residents</span>
					</div>
				</div>
			</div>

			<div class="col-lg-4 mt-4 mt-lg-0 text-center text-lg-right">
				<div class="bg-white p-3 rounded-lg text-dark shadow-lg d-inline-block text-center w-100" style="border-radius: 16px; max-width: 320px;">
					<img src="images/dti_logo.png" alt="DTI Logo" class="cmci-logo-img mb-2">
					<h6 class="font-weight-bold text-dark mb-1">DTI CMCI Competitiveness Profile</h6>
					<small class="text-muted d-block mb-2">Official Index Rating (2016-2024)</small>
					<a href="https://cmci.dti.gov.ph/lgu-profile.php?lgu=Tabina" target="_blank" class="btn btn-sm btn-primary rounded-pill font-weight-bold px-3 btn-block">
						<i class="fas fa-external-link-alt mr-1"></i> Verify on DTI Portal
					</a>
				</div>
			</div>
		</div>
	</div>

	<!-- Top National Highlights & Rank Badges -->
	<div class="row mb-4">
		<div class="col-lg-3 col-md-6 mb-3">
			<div class="card border-0 shadow-sm p-3 bg-white h-100" style="border-radius: 14px; border-left: 4px solid #10b981 !important;">
				<small class="text-uppercase text-muted font-weight-bold" style="font-size: 11px;">National Ranking #1</small>
				<h5 class="font-weight-bold text-dark mb-1">ARTA Citizen's Charter</h5>
				<small class="text-success font-weight-bold"><i class="fas fa-award mr-1"></i>1st Place National Compliance (Score: 2.00)</small>
			</div>
		</div>
		<div class="col-lg-3 col-md-6 mb-3">
			<div class="card border-0 shadow-sm p-3 bg-white h-100" style="border-radius: 14px; border-left: 4px solid #2563eb !important;">
				<small class="text-uppercase text-muted font-weight-bold" style="font-size: 11px;">National Ranking #1</small>
				<h5 class="font-weight-bold text-dark mb-1">Local Risk Assessment</h5>
				<small class="text-primary font-weight-bold"><i class="fas fa-shield-alt mr-1"></i>1st Place Disaster Preparedness (Score: 2.00)</small>
			</div>
		</div>
		<div class="col-lg-3 col-md-6 mb-3">
			<div class="card border-0 shadow-sm p-3 bg-white h-100" style="border-radius: 14px; border-left: 4px solid #8b5cf6 !important;">
				<small class="text-uppercase text-muted font-weight-bold" style="font-size: 11px;">National Ranking #2</small>
				<h5 class="font-weight-bold text-dark mb-1">Disaster Risk Reduction</h5>
				<small class="text-purple font-weight-bold"><i class="fas fa-check-circle mr-1"></i>2nd Place DRRMP Plan (Score: 1.977)</small>
			</div>
		</div>
		<div class="col-lg-3 col-md-6 mb-3">
			<div class="card border-0 shadow-sm p-3 bg-white h-100" style="border-radius: 14px; border-left: 4px solid #f59e0b !important;">
				<small class="text-uppercase text-muted font-weight-bold" style="font-size: 11px;">National Ranking #3</small>
				<h5 class="font-weight-bold text-dark mb-1">Business Permitting</h5>
				<small class="text-warning font-weight-bold"><i class="fas fa-file-invoice mr-1"></i>3rd Place Business Licensing (Score: 1.00)</small>
			</div>
		</div>
	</div>

	<!-- 5 CMCI Competitiveness Pillars breakdown -->
	<h4 class="font-weight-bold text-dark mb-3">
		<i class="fas fa-chart-bar text-primary mr-2"></i>CMCI 5 Pillars Competitiveness Evaluation
	</h4>

	<div class="row">
		<!-- 1. Government Efficiency Pillar -->
		<div class="col-lg-6 mb-4">
			<div class="pillar-card pillar-header-green p-4 h-100">
				<div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
					<div>
						<h5 class="font-weight-bold text-dark mb-0">Government Efficiency</h5>
						<small class="text-muted">Public service delivery, ARTA compliance & licensing</small>
					</div>
					<div class="text-right">
						<span class="rank-badge mb-1 d-inline-block">Rank: 589th</span>
						<span class="score-pill d-block">Score: 5.1476</span>
					</div>
				</div>
				<div class="metric-item d-flex justify-content-between align-items-center">
					<span>Compliance to ARTA Citizen's Charter</span>
					<span class="badge badge-success px-2 py-1">1st (2.0000)</span>
				</div>
				<div class="metric-item d-flex justify-content-between align-items-center">
					<span>Getting Business Permits Efficiency</span>
					<span class="badge badge-success px-2 py-1">3rd (1.0000)</span>
				</div>
				<div class="metric-item d-flex justify-content-between align-items-center">
					<span>Compliance to National Directives</span>
					<span class="badge badge-primary px-2 py-1">4th (1.9211)</span>
				</div>
				<div class="metric-item d-flex justify-content-between align-items-center">
					<span>Presence of Investment Promotion Unit</span>
					<span class="badge badge-info px-2 py-1">40th (0.0000)</span>
				</div>
				<div class="metric-item d-flex justify-content-between align-items-center">
					<span>Peace & Order Index</span>
					<span class="badge badge-secondary px-2 py-1">50th (0.0382)</span>
				</div>
				<div class="metric-item d-flex justify-content-between align-items-center">
					<span>Social Protection Capacity</span>
					<span class="badge badge-light border px-2 py-1">164th (0.0489)</span>
				</div>
			</div>
		</div>

		<!-- 2. Resiliency Pillar -->
		<div class="col-lg-6 mb-4">
			<div class="pillar-card pillar-header-blue p-4 h-100">
				<div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
					<div>
						<h5 class="font-weight-bold text-dark mb-0">Resiliency</h5>
						<small class="text-muted">Disaster preparedness, climate adaptation & risk assessment</small>
					</div>
					<div class="text-right">
						<span class="rank-badge mb-1 d-inline-block">Rank: 464th</span>
						<span class="score-pill d-block">Score: 10.8096</span>
					</div>
				</div>
				<div class="metric-item d-flex justify-content-between align-items-center">
					<span>Local Risk Assessment Preparedness</span>
					<span class="badge badge-success px-2 py-1">1st (2.0000)</span>
				</div>
				<div class="metric-item d-flex justify-content-between align-items-center">
					<span>Disaster Risk Reduction Plan (DRRMP)</span>
					<span class="badge badge-success px-2 py-1">2nd (1.9773)</span>
				</div>
				<div class="metric-item d-flex justify-content-between align-items-center">
					<span>Comprehensive Land Use Plan</span>
					<span class="badge badge-primary px-2 py-1">4th (1.9615)</span>
				</div>
				<div class="metric-item d-flex justify-content-between align-items-center">
					<span>Sanitary System Coverage</span>
					<span class="badge badge-primary px-2 py-1">6th (1.0000)</span>
				</div>
				<div class="metric-item d-flex justify-content-between align-items-center">
					<span>Early Warning System Readiness</span>
					<span class="badge badge-info px-2 py-1">79th (1.0198)</span>
				</div>
				<div class="metric-item d-flex justify-content-between align-items-center">
					<span>Annual Disaster Drill Compliance</span>
					<span class="badge badge-secondary px-2 py-1">89th (1.0016)</span>
				</div>
			</div>
		</div>

		<!-- 3. Economic Dynamism Pillar -->
		<div class="col-lg-6 mb-4">
			<div class="pillar-card pillar-header-orange p-4 h-100">
				<div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
					<div>
						<h5 class="font-weight-bold text-dark mb-0">Economic Dynamism</h5>
						<small class="text-muted">Local economy growth, business cost & employment generation</small>
					</div>
					<div class="text-right">
						<span class="rank-badge mb-1 d-inline-block">Rank: 377th</span>
						<span class="score-pill d-block">Score: 4.1787</span>
					</div>
				</div>
				<div class="metric-item d-flex justify-content-between align-items-center">
					<span>Cost of Doing Business</span>
					<span class="badge badge-primary px-2 py-1">36th (1.6556)</span>
				</div>
				<div class="metric-item d-flex justify-content-between align-items-center">
					<span>Cost of Living Score</span>
					<span class="badge badge-primary px-2 py-1">47th (1.8376)</span>
				</div>
				<div class="metric-item d-flex justify-content-between align-items-center">
					<span>Local Economy Growth Rate</span>
					<span class="badge badge-info px-2 py-1">91st (0.0029)</span>
				</div>
				<div class="metric-item d-flex justify-content-between align-items-center">
					<span>Business & Professional Organizations</span>
					<span class="badge badge-secondary px-2 py-1">118th (0.0002)</span>
				</div>
				<div class="metric-item d-flex justify-content-between align-items-center">
					<span>Local Economy Total Size</span>
					<span class="badge badge-light border px-2 py-1">166th (0.0006)</span>
				</div>
				<div class="metric-item d-flex justify-content-between align-items-center">
					<span>Active Establishments in Locality</span>
					<span class="badge badge-light border px-2 py-1">217th (0.3074)</span>
				</div>
			</div>
		</div>

		<!-- 4. Innovation Pillar -->
		<div class="col-lg-6 mb-4">
			<div class="pillar-card pillar-header-purple p-4 h-100">
				<div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
					<div>
						<h5 class="font-weight-bold text-dark mb-0">Innovation & Digital Readiness</h5>
						<small class="text-muted">ICT plans, online payment facilities & internet capability</small>
					</div>
					<div class="text-right">
						<span class="rank-badge mb-1 d-inline-block">Rank: 370th</span>
						<span class="score-pill d-block">Score: 3.7172</span>
					</div>
				</div>
				<div class="metric-item d-flex justify-content-between align-items-center">
					<span>Online Payment Facilities</span>
					<span class="badge badge-success px-2 py-1">1st (2.0000)</span>
				</div>
				<div class="metric-item d-flex justify-content-between align-items-center">
					<span>ICT Use: E-BPLS Software</span>
					<span class="badge badge-success px-2 py-1">2nd (0.0000)</span>
				</div>
				<div class="metric-item d-flex justify-content-between align-items-center">
					<span>Municipal ICT Master Plan</span>
					<span class="badge badge-primary px-2 py-1">3rd (0.6667)</span>
				</div>
				<div class="metric-item d-flex justify-content-between align-items-center">
					<span>New Technology Adoption</span>
					<span class="badge badge-info px-2 py-1">24th (0.0000)</span>
				</div>
				<div class="metric-item d-flex justify-content-between align-items-center">
					<span>Municipal Internet Capability</span>
					<span class="badge badge-info px-2 py-1">36th (1.0021)</span>
				</div>
				<div class="metric-item d-flex justify-content-between align-items-center">
					<span>Basic Internet Service Access</span>
					<span class="badge badge-light border px-2 py-1">284th (0.0484)</span>
				</div>
			</div>
		</div>
	</div>

	<!-- Bottom Official Reference Box -->
	<div class="card border-0 shadow-sm bg-white p-4" style="border-radius: 16px;">
		<div class="row align-items-center">
			<div class="col-md-8">
				<h5 class="font-weight-bold text-dark mb-1">
					<i class="fas fa-university text-primary mr-2"></i>Official Address & Contact Information
				</h5>
				<p class="text-muted small mb-0">
					<strong>Municipal Hall:</strong> Prk. Hibibo, Poblacion, Tabina, Zamboanga del Sur 7034 &bull; 
					<strong>Category:</strong> 4th Class Municipality
				</p>
			</div>
			<div class="col-md-4 text-md-right mt-3 mt-md-0">
				<a href="public_home.php" class="btn btn-outline-primary rounded-pill font-weight-bold px-4">
					<i class="fas fa-arrow-left mr-1"></i> Back to Public Portal
				</a>
			</div>
		</div>
	</div>

</div>

</body>

<?php require("footer.php");?>

</html>
