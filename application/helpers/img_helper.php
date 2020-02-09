<?php

function deleteImg($folder, $img)
{
    if (is_file(FCPATH . "assets/img/" . $folder . "/" . $img)) {
        unlink(FCPATH . "assets/img/" . $folder . "/" . $img);
    }
    if (is_file(FCPATH . "assets/img/thumbnail/" . $folder . "_" . $img)) {
        unlink(FCPATH . "assets/img/thumbnail/" . $folder . "_" . $img);
    }
}

function upload_file($name, $folder = '')
{
    $data = [
        'status' => false,
        'name' => ''
    ];
    $ci = get_instance();
    $config['allowed_types'] = 'gif|jpg|png|pdf';
    $config['max_size']      = '2048';
    $config['upload_path'] = './assets/img/' . $folder . '/';
    $ci->load->library('upload', $config);
    if ($ci->upload->do_upload($name)) {
        $data['status'] = true;
        $data['name'] = $ci->upload->data('file_name');
    } else {
        $data['name'] = $ci->upload->display_errors();
    }
    return $data;
}

function getThumb($img, $type = 'profile_')
{
    $ci = get_instance();
    $imgb = 'img/thumbnail/default.png';
    if (is_file(FCPATH . 'assets/img/thumbnail/' . $type . $img)) {
        $imgb = 'img/thumbnail/' . $type . $img;
    }
    return base_url() . 'assets/' . $imgb;
}
function getProfile($img, $type = null)
{
    $ci = get_instance();

    $imgb = 'img/profile/' . $img;
    $dir = FCPATH . 'assets/img/profile/' . $img;

    if ($type == 'thumbnail') {
        $dir = FCPATH . 'assets/img/thumbnail/profile_' . $img;
        $imgb = 'img/thumbnail/profile_' . $img;
    }
    if (!is_file($dir)) {
        $imgb = 'img/thumbnail/default.png';
    }
    return base_url('assets/')  . $imgb;
}
function getImg($img, $type = null)
{
    $ci = get_instance();
    $imgb = 'img/thumbnails/default.png';
    if (is_file(FCPATH . 'assets/img/' . $img)) {
        $imgb = 'img/' . $img;
        if ($type == 'thumbnail') {
            $imgb = 'img/thumbnails/' . $img;
        }
    }
    return $ci->assets . $imgb;
}

function _img_create_thumbs($file_name, $folder)
{
    $ci = get_instance();
    // Image resizing config
    $config = array(
        // Large Image
        // array(
        //     'image_library' => 'GD2',
        //     'source_image'  => './assets/images/' . $file_name,
        //     'maintain_ratio' => FALSE,
        //     'width'         => 700,
        //     'height'        => 467,
        //     'new_image'     => './assets/images/large/' . $file_name
        // ),
        // Medium Image
        // array(
        //     'image_library' => 'GD2',
        //     'source_image'  => './assets/images/' . $file_name,
        //     'maintain_ratio' => FALSE,
        //     'width'         => 600,
        //     'height'        => 400,
        //     'new_image'     => './assets/images/medium/' . $file_name
        // ),
        // Small Image
        array(
            'image_library' => 'GD2',
            'source_image'  => "./assets/img/{$folder}/" . $file_name,
            'maintain_ratio' => FALSE,
            'width'         => 100,
            'height'        => 100,
            'new_image'     => "./assets/img/thumbnail/{$folder}_" . $file_name
        )
    );

    $ci->load->library('image_lib', $config[0]);
    foreach ($config as $item) {
        $ci->image_lib->initialize($item);
        if (!$ci->image_lib->resize()) {
            return false;
        }
        $ci->image_lib->clear();
    }
}
