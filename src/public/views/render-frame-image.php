<link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css'>
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css'>
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.css'>
<link rel="stylesheet" href="<?= get_assets_uri("css/render-frame-image.css") ?>">

<div class="container text-center mb-5 mt-5">
    <div class="row">
        <div class="col-md-12">
            <h4><a href="https://codepen.io/piyushpd/pen/ZEOYKNZ" target="_blank">Crop Image File Upload with Modal View</a></h4>
        </div>
    </div>
</div>


<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <div class="confirm-identity">
                    <div class="ci-user d-flex align-items-center justify-content-center">
                        <div class="ci-user-picture">
                            <img src="https://image.flaticon.com/icons/svg/145/145867.svg" id="item-img-output" class="imgpreviewPrf img-fluid" alt="">
                        </div>
                    </div>
                    <div class="ci-user-btn text-center mt-4">
                        <a href="javascript:;" class="userEditeBtn btn-default bg-blue position-relative">
                            <input type="file" class="item-img file center-block filepreviewprofile">
                            Update Profile Photo
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade cropImageModal" id="cropImagePop" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <button type="button" class="close-modal-custom" data-dismiss="modal" aria-label="Close"><i class="feather icon-x"></i></button>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="modal-header-bg"></div>
                <div class="up-photo-title">
                    <h3 class="modal-title">Update Profile Photo</h3>
                </div>
                <div class="up-photo-content pb-5">

                    <div id="upload-demo" class="center-block">
                        <h5><i class="fas fa-arrows-alt mr-1"></i> Drag your photo as you require</h5>
                    </div>
                    <div class="upload-action-btn text-center px-2">
                        <button type="button" id="cropImageBtn" class="btn btn-default btn-medium bg-blue px-3 mr-2">Save Photo</button>
                        <button type="button" class="btn btn-default btn-medium bg-default-light px-3 ml-sm-2 replacePhoto position-relative">Replace Photo</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src='https://code.jquery.com/jquery-3.4.1.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js'></script>
<script src='https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js'></script>
<script src="<?php echo (get_script_uri('render-frame-image')); ?>">
    < /scrip>