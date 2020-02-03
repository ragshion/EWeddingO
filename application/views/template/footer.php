
  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      Anything you want
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2014-2019 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="<?=base_url('assets/')?>plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?=base_url('assets/')?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Select2 -->
<script src="<?=base_url('assets/')?>plugins/select2/js/select2.full.min.js"></script>
<script src="<?=base_url('assets/')?>plugins/sweetalert/sweetalert.min.js"></script>
<!-- DataTables -->
<script src="<?=base_url('assets/')?>plugins/datatables/jquery.dataTables.js"></script>
<script src="<?=base_url('assets/')?>plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<!-- AdminLTE App -->
<script src="<?=base_url('assets/')?>plugins/gmaps.js"></script>
<script src="<?=base_url('assets/')?>dist/js/adminlte.min.js"></script>
<script type="text/javascript">
	var map_peta, map_open = 0;
	$('#modal-tambah').on('shown.bs.modal', function (e) {
		if (map_open==0) {
			map_open=1;
			initAutocomplete();
		}
    })

    $('#cb_manual').change(function(){
        if (this.checked) {
            $('#latitude').attr("readonly", false);
            $('#longitude').attr("readonly", false);
        }else{
            $('#latitude').attr("readonly", true);
            $('#longitude').attr("readonly", true);
        }
    });

	$(function(){
		$('#pac-input').keypress(function(e){
			if (e.keyCode == 13) {
				e.preventDefault();
	        	return false;
			}
		});
		$('.select2').select2();
		$('.datatb').DataTable({
			responsive: true
		});
	})

	function initAutocomplete() {
        var map = new google.maps.Map(document.getElementById('map'), {
            center: {
                lat: -6.969078097777202,
                lng: 109.64613446499027
            },
                zoom: 15,
                gestureHandling: 'greedy'
        });

        var searchBox = new google.maps.places.SearchBox(document.getElementById('pac-input'));
        map.controls[google.maps.ControlPosition.TOP_CENTER].push(document.getElementById('pac-input'));

        google.maps.event.addListener(searchBox, 'places_changed', function() {
        searchBox.set('map', null);

        var places = searchBox.getPlaces();

        var bounds = new google.maps.LatLngBounds();
        var i, place;
        for (i = 0; place = places[i]; i++) {
        (function(place) {
            var marker = new google.maps.Marker({
                draggable:true,
                position: place.geometry.location
            });
            marker.bindTo('map', searchBox, 'map');
            markerCoords(marker);
            google.maps.event.addListener(marker, 'map_changed', function() {
                if (!this.getMap()) {
                    this.unbindAll();
                }
            });
            bounds.extend(place.geometry.location);

        }(place));

        }
        map.fitBounds(bounds);
        searchBox.set('map', map);
        map.setZoom(Math.min(map.getZoom(),15));


        });
    }

    function markerCoords(markerobject){
        google.maps.event.addListener(markerobject, 'dragend', function(evt){
            $('#latitude').val(evt.latLng.lat());
            $('#longitude').val(evt.latLng.lng());
        });

        google.maps.event.addListener(markerobject, 'drag', function(evt){
            $('#latitude').val(evt.latLng.lat());
            $('#longitude').val(evt.latLng.lng());
        });     
    }

    $(document).on('click','.del', function(){
        var href = $(this).attr('rel');
        swal({
            title: "Anda Yakin?",
            text: "Data yang telah di hapus tidak dapat dikembalikan!",
            type: "warning",
            showCancelButton: true,
            cancelButtonText:"Batal",
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Ya, Saya Yakin!!",
            closeOnConfirm:false
        },
            function(){
            swal({
                title:"Terhapus!",
                text: "Data yang anda maksud telah berhasil di hapus",
                type: "success"
            },
            function(){
                window.location = href;
            });
        });
        return false ;
    });

    $(document).on('click','.verif', function(){
        var href = $(this).attr('rel');
        swal({
            title: "Verifikasi Data",
            text: "Anda Akan Memverifikasi bahwa Data Pengusaha Ini Adalah Benar Adanya",
            type: "info",
            showCancelButton: true,
            cancelButtonText:"Batal",
            confirmButtonClass: "btn-primary",
            confirmButtonText: "Ya, Saya Yakin!!",
            closeOnConfirm:false
        },
            function(){
            swal({
                title:"Terhapus!",
                text: "Data yang anda maksud telah berhasil di verifikasi",
                type: "success"
            },
            function(){
                window.location = href;
            });
        });
        return false ;
    });

    function ubah_kategori(id){
    	$.post("<?=base_url('kategori/ubah/')?>",
        {
          id:id
        },
        function(data){
        	$('#modal-ubah').modal('toggle');
        	$('#id').val(data.id);
        	$('#kategori').val(data.kategori);
        });
    }

    $(function(){
        map_peta = new GMaps({
            div: '#map_peta',
            lat: -6.969078097777202,
            lng: 109.64613446499027,
            gestureHandling: 'greedy'
        });

        $.get( "<?=base_url('peta/latlng')?>", function( data ) {
            console.log(data);
            for (var i = 0; i < data.length; i++) {
                var z = data[i];
                map_peta.addMarker({
                    lat: z.lat,
                    lng: z.lng,
                    title: 'Marker Pengusaha',
                    infoWindow: {
                      content: '<center><a target="_blank" href="<?=base_url('uploads/')?>'+z.foto+'"><img width="100px" src="<?=base_url('uploads/')?>'+z.foto+'"></a></center><br/><p><table> <tr> <td>Nama Usaha</td><td>:</td><td>'+z.nama_usaha+'</td></tr><tr> <td>Alamat</td><td>:</td><td>'+z.alamat+'</td></tr><tr> <td>Kategori</td><td>:</td><td>'+z.kategori+'</td></tr></table></p>'
                    }
                });
            }
        });
    })
</script>
</body>
</html>