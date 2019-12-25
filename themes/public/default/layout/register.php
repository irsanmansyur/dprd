<?php $this->load->view($thema_load . 'template/header.php'); ?>
<div class="container">
    <div class="card">
        <div class="card-header card-header-rose card-header-icon">
            <div class="card-icon">
                <i class="material-icons">
                    group_add
                </i>
            </div>
            <h4 class="card-title">Form Registrasi</h4>
        </div>

        <div class="card-body">
            <div class="form-row">
                <div class="col-md-4 ml-auto">
                    <div class="text-center">
                        <div class="fileinput fileinput-new text-center" data-provides="fileinput">
                            <div class="fileinput-new thumbnail img-circle">
                                <img src="http://localhost/irsan/dprd/assets/img/thumbnail/default.png" alt="">
                            </div>
                            <div class="fileinput-preview fileinput-exists thumbnail img-circle"></div>
                            <div>
                                <span class="btn btn-round btn-rose btn-file">
                                    <span class="fileinput-new">Add Photo</span>
                                    <span class="fileinput-exists">Change</span>
                                    <input type="file" name="image">
                                </span>
                                <br>
                                <a href="#" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i>Remove</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="form-row">
                        <div class="form-group col-md-7 bmd-form-group">
                            <label for="name" class="bmd-label-floating ml-1">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12 bmd-form-group is-filled">
                            <label for="email" class="bmd-label-floating ml-1 ml-1">email</label>
                            <input type="text" class="form-control" id="email" name="email" value="" aria-required="true" aria-invalid="false">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6 ml-auto bmd-form-group">
                            <label for="no_hp" class="bmd-label-floating ml-1">no_hp</label>
                            <input type="text" class="form-control" id="no_hp" name="no_hp" value="">
                        </div>
                        <div class="form-group col-md-6 ml-auto bmd-form-group is-filled">
                            <label for="tgl_lahir" class="bmd-label-floating ml-1">Tgl Lahir</label>
                            <input type="date" class="form-control datepicker" id="tgl_lahir" name="tgl_lahir" value="12-19-1990" aria-invalid="false">
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6 bmd-form-group is-filled">
                    <label for="password" class="bmd-label-floating ml-1">password</label>
                    <input type="password" class="form-control" id="password" name="password" aria-invalid="false">

                </div>
                <div class="form-group col-md-5 ml-auto bmd-form-group">
                    <label for="password_confirm" class="bmd-label-floating ml-1">password_confirm</label>
                    <input type="password" class="form-control" id="password_confirm" name="password_confirm">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12 bmd-form-group">
                    <label for="alamat" class="bmd-label-floating ml-1">alamat</label>
                    <input type="text" class="form-control" id="alamat" name="alamat" value="">
                </div>
            </div>
            <div class="card-footer float-right">
                <button type="submit" class="btn btn-fill btn-rose" name="submit">Submit</button>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view($thema_load . 'template/footer.php'); ?>