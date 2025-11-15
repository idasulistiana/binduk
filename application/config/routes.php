<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;

|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

// 🔹 Default halaman pertama kali dibuka
$route['default_controller'] = 'ControllerLogin';

// 🔹 Login & Dashboard
$route['login']     = 'ControllerLogin';
$route['logout']    = 'ControllerLogin/logout';
$route['dashboard'] = 'ControllerDashboard';

// 🔹 Data Master
$route['siswa']     = 'ControllerDataMaster';
$route['siswa/edit_siswa/(:num)'] = 'ControllerDataMaster/edit_siswa/$1';
$route['siswa/update_siswa/(:num)'] = 'ControllerDataMaster/update_siswa/$1';
$route['siswa/delete_siswa/(:any)'] = 'ControllerDataMaster/delete_siswa/$1';
$route['siswa/add_siswa'] = 'ControllerDataMaster/add_siswa'; 
$route['siswa/download_siswa'] = 'ControllerDataMaster/download_siswa'; 
$route['siswa/import_siswa'] = 'ControllerDataMaster/import_siswa';

// 🔹 Data Alumni
$route['alumni']     = 'ControllerAlumni';
$route['alumni/update/(:num)'] = 'ControllerAlumni/update/$1';
$route['alumni/update_alumni/(:num)'] = 'ControllerAlumni/update_alumni/$1';
$route['alumni/delete_alumni/(:any)'] = 'ControllerAlumni/delete_alumni/$1';
// $route['siswa/update_siswa/(:num)'] = 'ControllerDataMaster/update_siswa/$1';
// $route['siswa/delete_siswa/(:any)'] = 'ControllerDataMaster/delete_siswa/$1';
// $route['siswa/add_siswa'] = 'ControllerDataMaster/add_siswa'; 
// $route['siswa/download_siswa'] = 'ControllerDataMaster/download_siswa'; 
// $route['siswa/import_siswa'] = 'ControllerDataMaster/import_siswa';

// 🔹 Mapel
$route['mapel']     = 'ControllerMapel';
$route['mapel/edit_mapel/(:num)'] = 'ControllerMapel/edit_mapel/$1';
$route['mapel/update_mapel/(:num)'] = 'ControllerMapel/update_mapel/$1';
$route['mapel/delete_mapel/(:any)'] = 'ControllerMapel/delete_mapel/$1';
$route['mapel/add_mapel'] = 'ControllerMapel/add_mapel'; 

// 🔹 Klapper
$route['riwayatkelas']   = 'ControllerKlapper';
$route['riwayatkelas/update_klapper/(:num)'] = 'ControllerKlapper/update_klapper/$1';
$route['riwayatkelas/delete_klapper/(:any)'] = 'ControllerKlapper/delete_klapper/$1';
$route['riwayatkelas/add_klapper'] = 'ControllerKlapper/add_klapper'; 
$route['riwayatkelas/download_kelas'] = 'ControllerKlapper/download_kelas'; 
$route['riwayatkelas/import_kelas'] = 'ControllerKlapper/import_kelas'; 


// 🔹 Kelas
$route['kelas']     = 'ControllerKelas';
$route['kelas/edit_kelas/(:num)'] = 'ControllerKelas/edit_kelas/$1';
$route['kelas/update_kelas/(:num)'] = 'ControllerKelas/update_kelas/$1';
$route['kelas/delete_kelas/(:any)'] = 'ControllerKelas/delete_kelas/$1';
$route['kelas/add_kelas'] = 'ControllerKelas/add_kelas';

// 🔹 Ekskul
$route['ekskul']                    = 'ControllerEkskul';
$route['ekskul/edit_ekskul/(:num)'] = 'ControllerEkskul/edit_ekskul/$1';
$route['ekskul/update_ekskul/(:num)'] = 'ControllerEkskul/update_ekskul/$1';
$route['ekskul/delete_ekskul/(:any)'] = 'ControllerEkskul/delete_ekskul/$1';
$route['ekskul/add_ekskul'] = 'ControllerEkskul/add_ekskul';


// 🔹 Nilai
$route['nilai']     = 'ControllerNilaiSiswa';
$route['nilai/all_nilai_siswa/(:any)'] = 'ControllerNilaiSiswa/all_nilai_siswa/$1';
$route['nilai/edit_siswa/(:num)'] = 'ControllerNilaiSiswa/edit_siswa/$1';
$route['nilai/update_nilai/(:num)'] = 'ControllerNilaiSiswa/update_nilai/$1';
$route['nilai/update_nilai_mulok/(:num)'] = 'ControllerNilaiSiswa/update_nilai_mulok/$1';
$route['nilai/update_nilai_ekskul/(:num)'] = 'ControllerNilaiSiswa/update_nilai_ekskul/$1';
$route['nilai/delete_nilai/(:any)'] = 'ControllerNilaiSiswa/delete_nilai/$1';
$route['nilai/store_nilai/(:any)'] = 'ControllerNilaiSiswa/store_nilai/$1';
$route['nilai/store_nilai_ekskul/(:any)'] = 'ControllerNilaiSiswa/store_nilai_ekskul/$1';
$route['nilai/import_nilai'] = 'ControllerNilaiSiswa/import_nilai';

// 🔹 Kehadiran
$route['kehadiran'] = 'ControllerRekapKehadiran';
$route['kehadiran/edit_siswa/(:num)'] = 'ControllerRekapKehadiran/edit_siswa/$1';
$route['kehadiran/download_rekap_kehadiran'] = 'ControllerRekapKehadiran/download_rekap_kehadiran';
$route['kehadiran/delete_rekap/(:any)'] = 'ControllerRekapKehadiran/delete_rekap/$1';
$route['kehadiran/add_rekap'] = 'ControllerRekapKehadiran/add_rekap';
$route['kehadiran/import_rekap_kehadiran'] = 'ControllerRekapKehadiran/import_rekap_kehadiran';

// 🔹 Buku Induk
$route['bukuinduk'] = 'ControllerBukuIndukSiswa';
$route['bukuinduk/detail/(:any)'] = 'ControllerBukuIndukSiswa/detail/$1';


// 🔹 Error & default setting
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;



