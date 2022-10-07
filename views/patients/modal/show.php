<div class="modal fade" id="show-modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header no-bd">
                <h5 class="modal-title">
                    <i class="fas fa-id-card pr-1 pr-1"></i> PATIENT PROFILE
                </h5>
                <button type="button" class="btn btn-circle" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" data-placement="top" title="Close Modal">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-lg-12">
                        <ul class="nav nav-pills nav-secondary nav-pills-no-bd" id="pills-tab-without-border" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="pills-informations-tab-nobd" data-toggle="pill" href="#pills-informations-nobd" role="tab" aria-controls="pills-profile-nobd" aria-selected="false"><i class="fas fa-info-circle pr-1"></i>   Personal Informations</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pills-transactions-tab-nobd" data-toggle="pill" href="#pills-transactions-nobd" role="tab" aria-controls="pills-transactions-nobd" aria-selected="false"><i class="fas fa-exchange-alt pr-1"></i> Transaction Lists</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pills-medications-tab-nobd" data-toggle="pill" href="#pills-medications-nobd" role="tab" aria-controls="pills-medications-nobd" aria-selected="false"><i class="fas fa-medkit pr-1"></i> Medication Lists</a>
                            </li>
                        </ul>
                        <div class="tab-content mt-2 mb-3" id="pills-without-border-tabContent">
                            <div class="tab-pane fade show active" id="pills-informations-nobd" role="tabpanel" aria-labelledby="pills-informations-tab-nobd">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-title"><i class="fas fa-info-circle pr-1"> </i>  Personal Informations</div>
                                    </div>
                                    <div class="card-body">
                                        <!-- Profile -->
                                        <?php include_once('./profile.php'); ?>
                                        <!-- End Profile -->
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="pills-transactions-nobd" role="tabpanel" aria-labelledby="pills-transactions-tab-nobd">
                                <div class="card-header">
                                    <div class="card-title"><i class="fas fa-exchange-alt pr-1"> </i>  Transactions Lists</div>
                                </div>
                                <div class="card-body">
                                    <!-- Case Involves Table -->
                                    <?php include_once('table/transaction-lists.php'); ?>
                                    <!-- End Case Involves Table -->
                                </div>
                            </div>

                            <div class="tab-pane fade" id="pills-medications-nobd" role="tabpanel" aria-labelledby="pills-medications-tab-nobd">
                                <div class="card-header">
                                    <div class="card-title"><i class="fas fa-medkit pr-1"> </i>  Medication Lists</div>
                                </div>
                                <div class="card-body">
                                    <!-- Case Involves Table -->
                                    <?php include_once('table/medication-lists.php'); ?>
                                    <!-- End Case Involves Table -->
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer no-bd">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times-circle"></i> Close</button>
            </div>
        </div>
    </div>
</div>