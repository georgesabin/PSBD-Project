<?php include_once('header.php'); ?>
<!-- ============================================================== -->
<!-- Container fluid  -->
<!-- ============================================================== -->
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">Rezervare</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <button type="button" class="btn waves-effect waves-light btn-danger pull-right hidden-sm-down" data-toggle="modal" data-target="#myModal">Popup</button>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <!-- Row -->
    <div class="row">
        <!-- Column -->
        <div class="col-lg-4 col-xlg-3 col-md-5">
            <div class="card">
                <div class="card-body">
                    <center class="m-t-30"> <img src="../assets/images/users/5.jpg" class="img-circle" width="150" />
                        <h4 class="card-title m-t-10">Hanna Gover</h4>
                        <h6 class="card-subtitle">Accoubts Manager Amix corp</h6>
                        <div class="row text-center justify-content-md-center">
                            <div class="col-4"><a href="javascript:void(0)" class="link"><i class="icon-people"></i> <font class="font-medium">254</font></a></div>
                            <div class="col-4"><a href="javascript:void(0)" class="link"><i class="icon-picture"></i> <font class="font-medium">54</font></a></div>
                        </div>
                    </center>
                </div>
            </div>
        </div>
        <!-- Column -->
        <!-- Column -->
        <div class="col-lg-8 col-xlg-9 col-md-7">
            <div class="card">
                <div class="card-body">
                    <form id="rezervare" class="form-horizontal form-material" action="form-response.php" method="POST">
                        <div class="form-group">
                            <label class="col-md-12">Nume</label>
                            <div class="col-md-12">
                                <input type="text" placeholder="Introdu numele" class="form-control form-control-line" name="rezervare_nume">
                                <label class="text-danger" style="font-style: italic; font-size: 12px;"></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">CNP</label>
                            <div class="col-md-12">
                                <input type="text" placeholder="Introdu CNP" class="form-control form-control-line" name="rezervare_cnp">
                                <label style="font-style: italic; font-size: 12px;"></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Telefon</label>
                            <div class="col-md-12">
                                <input type="text" placeholder="Introdu telefon" class="form-control form-control-line" name="rezervare_telefon">
                                <label style="font-style: italic; font-size: 12px;"></label>
                            </div>
                        </div>
                    </form>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <button class="btn btn-success" onclick="submit_form('rezervare');">Rezerva</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column -->
    </div>
    <!-- Row -->
    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->
</div>

<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Modal Header</h4>
        </div>
        <div class="modal-body">
          <p>Some text in the modal.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
</div>
<!-- ============================================================== -->
<!-- End Container fluid  -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<?php include_once('footer.php'); ?>