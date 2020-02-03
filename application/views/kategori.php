  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <div class="modal fade" id="modal-tambah">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Tambah Data Kategori</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <!-- form start -->
            <form role="form" method="post" action="<?=base_url('kategori/simpan')?>">
              <div class="form-group">
                <label>Kategori</label>
                <input type="text" class="form-control" placeholder="Kategori" name="kategori">
              </div>
              <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    <div class="modal fade" id="modal-ubah">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Ubah Data Kategori</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <!-- form start -->
            <form role="form" method="post" action="<?=base_url('kategori/update')?>">
              <input type="hidden" name="id" id="id">
              <div class="form-group">
                <label>Kategori</label>
                <input type="text" class="form-control" id="kategori" placeholder="Kategori" name="kategori">
              </div>
              <button type="submit" class="btn btn-info">Perbarui</button>
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
            <h1 class="m-0 text-dark">Data Kategori</h1>
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
                <h5 class="m-0">Data Kategori</h5>
              </div>
              <div class="card-body">
                <?= $this->session->flashdata('alert'); ?>
                <button class="btn bg-gradient-info" data-toggle="modal" data-target="#modal-tambah">Tambah Data</button>
                <hr>
                <table class="table table-bordered table-striped datatb">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Kategori</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $no = 1; foreach ($data as $d): ?>
                      <tr>
                        <td><?=$no?></td>
                        <td><?=$d['kategori']?></td>
                        <td><a href="#" class="btn bg-gradient-warning" onclick="ubah_kategori('<?=$d['id']?>')"><i class="fas fa-edit"></i> Ubah</a> <a href="#" class="btn bg-gradient-danger del" rel="<?= base_url('kategori/hapus/').$d['id'];?>"><i class="fas fa-trash-alt"></i> Hapus</a></td>
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