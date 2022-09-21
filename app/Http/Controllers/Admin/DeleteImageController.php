<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class DeleteImageController extends Controller
{
    public function __invoke( $id )
    {
        $media =   Media::find($id);
        $media->delete();
        // return back()->with('success', 'image deleted successfully'); 
        return response(['status' => true], 200);

    }
}
