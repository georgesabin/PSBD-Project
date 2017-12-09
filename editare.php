<?php
include_once('header.php'); 

// $stid = oci_parse($conn, "SELECT tip FROM camere");
// $stid = oci_parse($conn, "INSERT INTO camere values (3, 102, 1, 'double')");
// oci_execute($stid);

?>
<!-- ============================================================== -->
<!-- Container fluid  -->
<!-- ============================================================== -->
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">Editare rezervare</h3>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="row">
        <!-- column -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <!-- <form id="editare" class="form-horizontal form-material" method="post" action="form-response-editare.php"> -->
                        <div class="form-group">
                            <label class="col-md-12">Introdu CNP</label>
                            <div class="col-md-12">
                                <input type="number" class="form-control" name="editare_cnp" value="Introdu CNP" />
                                <label class="text-danger" style="font-style: italic; font-size: 12px;"></label>
                            </div>
                        </div>
                    <!-- </form> -->
                    <div class="form-group">
                        <div class="col-sm-12">
                            <button id="editare-rezervare" class="btn btn-success" >Editare rezervare</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->
</div>
<?php include_once('footer.php'); ?>