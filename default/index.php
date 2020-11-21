     <?php
	 require_once("../general/header.php");
	 require_once("../general/menu-master.php");
	 ?>

            
            <div class="main-content">

                <div class="page-content">
                 
                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-flex align-items-center justify-content-between">
                                <h4 class="page-title mb-0 font-size-18">Dashboard</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item active">Welcome to Qovex Dashboard</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    
                </div>
                <!-- End Page-content -->

            <?php require_once("../general/footer.php");?>    
			<script>

	           document.getElementById("loginName").innerHTML='<?=$__FirstName?>'+ ''+'<?=$__LastName?>';
	
            </script>