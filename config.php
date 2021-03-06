<?php 
include_once('../wp-config.php');
function clearTags($tagArray,$content){
    foreach ($tagArray as $tag) {
       $content = preg_replace($tag, "", $content);
    }
    return $content;
}
function curl($url, $post=false)
{
    $user_agent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; tr; rv:1.9.0.6) Gecko/2009011913 Firefox/3.0.6';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, $post ? true : false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post ? $post : false);
    curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0); 
    curl_setopt($ch, CURLOPT_TIMEOUT, 400);
    $icerik = curl_exec($ch);
    curl_close($ch);
    return $icerik;
}
function addPost($title,$post)
{
    global $wpdb;
    $query = $wpdb->prepare(
        'SELECT ID FROM ' . $wpdb->posts . '
        WHERE post_title = %s and post_status = "publish" ',
        $title
    );
    $wpdb->query( $query );
    if (!$wpdb->num_rows) {
    $post_id = wp_insert_post($post);
    return $post_id;
}else{
    return null;
}
}

function addImgtoThumbnail($file,$post_id )
{
    $upload_dir = wp_upload_dir();
    $image_data = file_get_contents($file);
    if($image_data){
    $filename = basename($file);

    $wp_filetype = wp_check_filetype($filename, null );
    $attachment = array(
            'post_mime_type' => $wp_filetype['type'],
            'post_title' => sanitize_file_name($filename),
            'post_content' => '',
            'post_status' => 'inherit'
    );
    $attach_id = wp_insert_attachment( $attachment, $file, $post_id );
    set_post_thumbnail( $post_id, $attach_id );
    return true;
}else{
    return false;
}
}