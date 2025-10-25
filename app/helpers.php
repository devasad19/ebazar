<?php 




if (!function_exists('fileUpload')) {
    function fileUpload($file, $path)
    {
        $originName = $file->getClientOriginalName();
        $fileName = pathinfo($originName, PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $filename = $fileName.'_'.time().'.'.$extension;
        $thumbnails = $file->move($path, $filename);

        return $filename;

    }
}

function isValidMobile($mobile, $method)
{
    // remove spaces or unwanted chars
    $mobile = preg_replace('/\D/', '', $mobile);

    if (strtolower($method) === 'rocket') {
        return preg_match('/^\d{12}$/', $mobile);
    }

    // for others (bkash, nagad, etc)
    return preg_match('/^\d{11}$/', $mobile);
}


