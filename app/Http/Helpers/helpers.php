<?php

function format_uang($angka, $desimal = 0) {
    return number_format($angka, $desimal, ",", ".");
}
function format_uang_rp($angka, $desimal = 0) {
    return 'Rp. ' . format_uang($angka, $desimal);
}



function terbilang ($angka) {
    $angka = abs($angka);
    $baca  = array('', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas');
    $terbilang = '';

    if ($angka < 12) { // 0 - 11
        $terbilang = ' ' . $baca[$angka];
    } elseif ($angka < 20) { // 12 - 19
        $terbilang = terbilang($angka -10) . ' belas';
    } elseif ($angka < 100) { // 20 - 99
        $terbilang = terbilang($angka / 10) . ' puluh' . terbilang($angka % 10);
    } elseif ($angka < 200) { // 100 - 199
        $terbilang = ' seratus' . terbilang($angka -100);
    } elseif ($angka < 1000) { // 200 - 999
        $terbilang = terbilang($angka / 100) . ' ratus' . terbilang($angka % 100);
    } elseif ($angka < 2000) { // 1.000 - 1.999
        $terbilang = ' seribu' . terbilang($angka -1000);
    } elseif ($angka < 1000000) { // 2.000 - 999.999
        $terbilang = terbilang($angka / 1000) . ' ribu' . terbilang($angka % 1000);
    } elseif ($angka < 1000000000) { // 1000000 - 999.999.990
        $terbilang = terbilang($angka / 1000000) . ' juta' . terbilang($angka % 1000000);
    }

    return $terbilang;
}

function tanggal_indonesia($tgl, $tampil_hari = true)
{
    // Validasi awal: cek apakah $tgl kosong atau tidak dalam format yang diharapkan
    if (empty($tgl) || !strtotime($tgl)) {
        return "Tanggal tidak valid";
    }

    // Definisi nama hari dan nama bulan
    $nama_hari = [
        'Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum\'at', 'Sabtu'
    ];
    $nama_bulan = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    // Konversi tanggal ke format Unix timestamp
    $timestamp = strtotime($tgl);

    // Ambil elemen tanggal, bulan, dan tahun
    $hari = $nama_hari[date('w', $timestamp)];
    $tanggal = date('d', $timestamp);
    $bulan = $nama_bulan[(int) date('m', $timestamp)];
    $tahun = date('Y', $timestamp);
    $jam = date('H:i', $timestamp);

    // Format output
    if ($tampil_hari) {
        return "$hari, $tanggal $bulan $tahun / $jam";
    }

    return "$tanggal $bulan $tahun / $jam";
}

function parseHarga($input) {
    $input = str_replace('.', '', $input);      // Hilangkan titik ribuan
    $input = str_replace(',', '.', $input);     // Ubah koma ke titik desimal
    return (float) $input;                      // Konversi ke float
}


use Carbon\Carbon;

function tanggal($tgl, $format = 'd-m-Y')
{
    try {
        return Carbon::parse($tgl)->format($format);
    } catch (\Exception $e) {
        return '-';
    }
}


function tambah_nol_didepan($value, $threshold = null)
{
    return sprintf("%0". $threshold . "s", $value);
}