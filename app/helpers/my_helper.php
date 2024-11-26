<?php

use Carbon\Carbon;

function formatDate(?string $date, string $format = 'd-m-Y'): ?string
{
    if (!$date) {
        return null; // Jika tanggal null, kembalikan null
    }
    try {
        return Carbon::parse($date)->format($format);
    } catch (\Exception $e) {
        return null; // Jika parsing gagal, kembalikan null
    }
}
