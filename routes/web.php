<?php

use App\Models\OutGoing;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});


Route::get('/letter/{outGoing}', function (OutGoing $outGoing) {
    $company = $outGoing->company;
    return view('filament.outgoing.letter', [
        'issue_number' => $outGoing->number,
        'receiver' => $outGoing->receiver,
        'subject' => $outGoing->subject,
        'body' => $outGoing->body,
        'title' => $outGoing->title,
        'letterhead' => $company->letterhead,
        'ceo_name' => $company->ceo_name,
    ]);
})->name('letter.preview');

