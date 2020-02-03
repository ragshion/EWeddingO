  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <div class="modal fade" id="modal-tambah">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Tambah Data Pengusaha</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <!-- form start -->
              <form role="form" method="post" action="<?=base_url('pengusaha/simpan')?>" enctype='multipart/form-data'>
                <div class="form-group">
                  <label>Nama Usaha</label>
                  <input type="text" class="form-control" id="nama_usaha" placeholder="Nama Usaha" name="nama_usaha">
                </div>
                <div class="form-group">
                  <label>Alamat</label>
                  <textarea class="form-control" name="alamat" placeholder="Alamat"></textarea>
                </div>
                <div class="form-group">
                  <label>No Whatsapp</label>
                  <input type="text" class="form-control" id="no_wa" placeholder="Nomor Whatsapp" name="no_wa">
                </div>
                <div class="form-group">
                  <label>Deskripsi</label>
                  <textarea class="form-control" name="deskripsi" placeholder="Deskripsi" rows="4"></textarea>
                </div>
                <div class="form-group">
                  <label>Kategori</label>
                  <select class="form-control select2" multiple="multiple" data-placeholder="Pilih Kategori"  style="width: 100%;" name="kategori[]">
                    <?php foreach ($kategori as $k): ?>
                      <option value="<?=$k['id']?>"><?=$k['kategori']?></option>
                    <?php endforeach ?>
                  </select>
                </div>
                <div class="row">
                  <div class="container-fluid">
                    <div class="form-group">
                      <label class="form-label">Koordinat</label>
                      <div class="row">
                        <div class="col-sm-4">
                          <div class="form-group mt-0 pb-0">
                              <label>Manual ?</label>
                              <div class="custom-control custom-checkbox">
                                  <input type="checkbox" class="custom-control-input" id="cb_manual">
                                  <label class="custom-control-label" for="cb_manual">Manual</label>
                              </div>
                          </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group mt-0 pb-0">
                                <div class="form-line">
                                    <label>Latitude</label>
                                    <input type="text" class="form-control" name="lat" id="latitude" readonly="">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group mt-0 pb-0">
                                <div class="form-line">
                                    <label>Longitude</label>
                                    <input type="text" class="form-control" name="lng" id="longitude" readonly="">
                                </div>
                            </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="container-fluid">
                    <div class="col-12">
                        <div class="form-group">
                        <input id="pac-input" class="form-control col-md-6 mt-2" type="text" placeholder="Cari Tempat. . ." style="background-color: white">
                    </div>
                        <div class="card">
                          <div id="map" style="width: 100%; height: 400px;"></div>
                        </div>
                    </div>
                  </div>
                </div>  
                <div class="form-group">
                  <label for="exampleInputFile">File input</label>
                  <input type="file" name="userFiles[]" class="form-control" multiple="">
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
              </form>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Data Pengusaha</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">

            <div class="card card-primary card-outline">
              <div class="card-header">
                <h5 class="m-0">Data Pengusaha</h5>
              </div>
              <div class="card-body">
                <?= $this->session->flashdata('alert'); ?>
                <button class="btn bg-gradient-success" data-toggle="modal" data-target="#modal-tambah">Tambah Data</button>
                <hr>
                <table class="table table-bordered table-striped datatb table-responsive">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Nama Usaha</th>
                      <th>Alamat</th>
                      <th>Nomor WhatsApp</th>
                      <th>Deskripsi</th>
                      <th>Kategori</th>
                      <th>Status</th>
                      <th>Foto</th>
                      <th>Lokasi</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $no = 1; foreach ($data as $d): ?>
                      <tr>
                        <td><?=$no?></td>
                        <td><?=$d['nama_usaha']?></td>
                        <td><?=$d['alamat']?></td>
                        <td><?=$d['no_wa']?></td>
                        <td><?=$d['deskripsi']?></td>
                        <td><?=$d['kategori']?></td>
                        <?php if ($d['status'] == '0') {
                          $status = '<span class="badge badge-warning">Pending</span>';
                        } else {
                          $status = '<span class="badge badge-info">Terverifikasi</span>';
                        } ?>
                        <td><?=$status?></td>
                        <td><img src="<?=base_url('uploads/').$d['foto']?>" width="50"></td>
                        <td><a href="https://www.google.com/maps?daddr=<?=$d['latlng']?>" class="btn bg-gradient-primary" target="_blank"><i class="fas fa-directions"></i> Lokasi</a></td>
                        <td><a href="#" class="btn bg-gradient-danger del" rel="<?= base_url('pengusaha/hapus/').$d['id_usaha'];?>"><i class="fas fa-trash-alt"></i> Hapus</a> <a href="#" class="btn bg-gradient-info verif" rel="<?= base_url('pengusaha/verifikasi/').$d['id_usaha'];?>"><i class="fas fa-check"></i> Verifikasi</a></td>
                      </tr>
                    <?php $no += 1; endforeach ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->