<?php
/**
 * AFO - Feb 17, 2016
 * The purpose of this template is to load the PDFs in a smart way, 
 * while also redirecting the attachment pages from a broken template to the permalink of the file
 */

$attachment = get_attached_file( get_the_ID() );
$filename = basename($attachment);
$file_extension = strtolower(substr(strrchr($filename,"."),1));

switch (strtolower($file_extension)) {
    case "pdf": 
        $ctype="application/pdf";
        if (file_exists($attachment)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename='.$filename.'');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($attachment));
            // while (readfile($attachment));
            set_time_limit(0);
            $file = @fopen($attachment,"rb");
            while(!feof($file))
            {
                print(@fread($file, 1024*8));
                ob_flush();
                flush();
            }
            die();
        }
    default:
        header( 'Location: '.wp_get_attachment_url() );
}