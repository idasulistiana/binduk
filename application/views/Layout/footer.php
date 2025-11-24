<footer class="main-footer">

</footer>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="<?= base_url('asset/AdminLTE/') ?>plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?= base_url('asset/AdminLTE/') ?>plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->

<!-- Bootstrap 4 -->
<script src="<?= base_url('asset/AdminLTE/') ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- daterangepicker -->
<script src="<?= base_url('asset/AdminLTE/') ?>plugins/moment/moment.min.js"></script>
<script src="<?= base_url('asset/AdminLTE/') ?>plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="<?= base_url('asset/AdminLTE/') ?>plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="<?= base_url('asset/AdminLTE/') ?>plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="<?= base_url('asset/AdminLTE/') ?>plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- jQuery Knob -->
<script src="<?= base_url('asset/AdminLTE/') ?>plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- AdminLTE App -->
<script src="<?= base_url('asset/AdminLTE/') ?>dist/js/adminlte.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?= base_url('asset/AdminLTE/') ?>dist/js/pages/dashboard.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?= base_url('asset/AdminLTE/') ?>dist/js/demo.js"></script>
<script src="<?= base_url('asset/AdminLTE/') ?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url('asset/AdminLTE/') ?>plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url('asset/AdminLTE/') ?>plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url('asset/AdminLTE/') ?>plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>

<script src="<?= base_url('asset/AdminLTE/') ?>dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?= base_url('asset/AdminLTE/') ?>dist/js/demo.js"></script>
<script src="<?= base_url('asset/AdminLTE/') ?>plugins/select2/js/select2.full.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

<!-- page script -->
<script>

    // Ketika tombol delete diklik


// ajax data table data siswa

    window.addEventListener("load", function() {
        document.body.style.display = 'block';
            var logo = document.getElementById('logo-sekolah');
        if (logo) {
            // Mengubah opacity menjadi 0.8 (nilai aslinya) untuk membuatnya terlihat
            logo.style.opacity = '0.8'; 
        }
    });
</script>


<script>
$(document).ready(function() {
 
    // Target tombol toggle (biasanya di navbar)
    var $toggleBtn = $('[data-widget="pushmenu"] i'); // ambil elemen <i> di dalam tombol

    // Fungsi untuk mengganti ikon berdasarkan keadaan sidebar
    function updateToggleIcon() {
        if ($('body').hasClass('sidebar-collapse')) {
            // Sidebar collapsed → tampilkan burger icon
            $toggleBtn.removeClass('fa-arrow-left').addClass('fa-bars');
        } else {
            // Sidebar expanded → tampilkan arrow-left icon
            $toggleBtn.removeClass('fa-bars').addClass('fa-arrow-left');
        }
    }

    // Jalankan sekali saat halaman pertama kali dimuat
    updateToggleIcon();

    // Jalankan setiap kali tombol toggle diklik
    $(document).on('click', '[data-widget="pushmenu"]', function() {
        // Delay sedikit supaya class sidebar-collapse sempat berubah
        setTimeout(updateToggleIcon, 300);
    });

    // Tambahan: hapus efek transisi glitch
    var $sidebarHost = $('.main-sidebar .os-host');
    if ($('body').hasClass('sidebar-collapse')) {
        $sidebarHost.removeClass('os-host-transition');
        console.log("Kelas transisi dinamis pada sidebar telah dihapus.");
    }
});


// Jika Anda menggunakan tombol toggle default (AdminLTE)
$(document).on('expanded.lte.pushmenu collapsed.lte.pushmenu', function (e) {
    var $sidebarHost = $('.main-sidebar .os-host');
    if ($('body').hasClass('sidebar-collapse')) {
        $sidebarHost.removeClass('os-host-transition');
    } else {
        // Kembalikan kelas jika sidebar dibuka kembali (agar transisi normal)
        $sidebarHost.addClass('os-host-transition');
    }
});

</script>

<script>
    $(function() {
        //Initialize Select2 Elements
        $('.select2').select2()

    })
</script>
<script>
    $(function() {
        $("#example1, #tableAlumni").DataTable({
            "responsive": true,
            "autoWidth": false,
            "pageLength": 25, // jumlah data default per halaman
            "lengthMenu": [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"] ],
        });

        //Date range picker
        $('#reservationdate').datetimepicker({
            format: 'YYYY-MM-DD'
        });
    });
</script>
<script>
    $("#create_user").click(function() {
        if ($("#show_input_user").first().is(":hidden")) {
            $("#show_input_user").show("slow");
        } else {
            $("#show_input_user").slideUp();
        }
    });
</script>
<script>
    window.setTimeout(function() {
        $(".alert").fadeTo(500, 0).slideUp(500, function() {
            $(this).remove();
        });
    }, 3000)
</script>
<script>
    console.log = function() {}
    $("#produk").on('change', function() {

        $(".qty").html($(this).find(':selected').attr('data-qty'));
        $(".qty").val($(this).find(':selected').attr('data-qty'));


        $(".id").html($(this).find(':selected').attr('data-id'));
        $(".id").val($(this).find(':selected').attr('data-id'));

        $(".stok").html($(this).find(':selected').attr('data-stok'));
        $(".stok").val($(this).find(':selected').attr('data-stok'));
    });
</script>
<script>
    console.log = function() {}
    $("#produk_admin").on('change', function() {

        $(".harga").html($(this).find(':selected').attr('data-harga'));
        $(".harga").val($(this).find(':selected').attr('data-harga'));

        $(".price").html($(this).find(':selected').attr('data-price'));
        $(".price").val($(this).find(':selected').attr('data-price'));


        $(".name").html($(this).find(':selected').attr('data-name'));
        $(".name").val($(this).find(':selected').attr('data-name'));

        $(".sisa").html($(this).find(':selected').attr('data-sisa'));
        $(".sisa").val($(this).find(':selected').attr('data-sisa'));


    });
</script>
<script>
  $(document).ready(function () {
    // Default: sidebar mini + collapse
    $('body').addClass('sidebar-mini sidebar-collapse');

    // Klik tombol burger → toggle buka/tutup sidebar
    $('[data-widget="pushmenu"]').click(function (e) {
      e.preventDefault();
      $('body').toggleClass('sidebar-collapse');
    });
  });
</script>

<script>
    //defined nama kota 
$(document).ready(function() {
    var cities = [
        "Jakarta", "Surabaya", "Bandung", "Medan", "Semarang",
        "Yogyakarta", "Makassar", "Denpasar", "Palembang", "Balikpapan"
        // Bisa tambahkan semua kota/kabupaten di Indonesia
    ];

    $("#tempat_lahir").autocomplete({
        source: cities,
        minLength: 1, // mulai saran setelah ketik 1 karakter
        autoFocus: true // fokus otomatis ke item pertama
    });
});
</script>
<script>
//fungsi autocomplete
function initAutocomplete(selector) {
    $(selector).autocomplete({
        source: function(request, response) {
            let term = request.term;
            let tahun = parseInt(term);

            // ✅ hanya munculkan kalau input minimal 3 digit angka
            if (!isNaN(tahun) && term.length >= 3) {
                let suggestions = [];
                for (let i = -1; i <= 10; i++) {
                    let t1 = tahun + i;
                    let t2 = t1 + 1;
                    suggestions.push(`${t1}/${t2}`);
                }
                response(suggestions);
            } else {
                response([]);
            }
        },
        minLength: 0 // biar bisa kita kontrol sendiri
    }).focus(function() {
        // cuma munculkan saat fokus kalau isi input sudah >= 3 digit
        if ($(this).val().length >= 3) {
            $(this).autocomplete("search", $(this).val());
        }
    });
}
// untuk autocomplete no indk siswa di klapper
$(function() {
    $("#no_induk").autocomplete({
        source: "<?= base_url('ControllerKlapper/get_siswa_autocomplete') ?>",
        minLength: 2, // mulai muncul saran setelah 2 karakter
        select: function(event, ui) {
            $("#no_induk").val(ui.item.value); // set input ke no_induk
            return false;
        }
    });
});


// autocomplete tahun ajar rekap absen
$(function() {
    // Daftar tahun ajaran yang akan muncul otomatis
    var tahunAjaranList = [];
    var startYear = 2020; // kamu bisa ubah sesuai kebutuhan
    var endYear = new Date().getFullYear() + 2; // hingga dua tahun ke depan

    for (var i = startYear; i <= endYear; i++) {
        tahunAjaranList.push(i + "/" + (i + 1));
    }

    // Aktifkan autocomplete
    $("#tahun_ajaran").autocomplete({
        source: tahunAjaranList,
        minLength: 0 // tampilkan semua saat diklik
    }).focus(function() {
        $(this).autocomplete("search", "");
    });
});

//autocomplete no induk di menu rekap absen
$(function() {
    $("#no_induk_siswa").autocomplete({
        source: function(request, response) {
            $.ajax({
                url: "<?= base_url('ControllerRekapKehadiran/get_siswa') ?>",
                dataType: "json",
                data: {
                    term: request.term
                },
                success: function(data) {
                    response(data);
                }
            });
        },
        minLength: 1,
        select: function(event, ui) {
            $('#no_induk_siswa').val(ui.item.value);
            return false;
        }
    });
});

$(document).ready(function() {
    // Jika URL mengandung #tab_2
    if (window.location.hash === '#tab_2') {
        // Tunggu sedikit untuk memastikan tab siap
        setTimeout(function() {
            var triggerEl = document.querySelector('#tab_tambah_rekap');
            if (triggerEl) {
                var tab = new bootstrap.Tab(triggerEl);
                tab.show();
            }
        }, 100); // 100ms delay
    }
});
//=========== ADD Data TABLE=======

$(".table-rekapkehadiran-siswa").DataTable();
</script>

</body>
</html>
