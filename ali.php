<?php
    header("Content-type:application/json;charset=utf-8");
    $file = $_FILES['image'];
    $name = $file['name'];
    $tmp_name = $file['tmp_name'];
    $type = strtolower(substr($name,strrpos($name,'.')+1)); //得到文件类型，并且都转化成小写
    $tmp_name_type = $tmp_name.'.'.$type;
    if(file_exists($tmp_name)){
    rename($tmp_name,$tmp_name_type);
    }

    $ch = curl_init();
    $url = 'https://tm.xiami.com/uploadFile?scene=img';
    $post_data = array(
        'file' => new \CURLFile(realpath($tmp_name_type)),
        'source' => 'musician-avatar',
        'uploadType' => 'img',
        'filename' => $name
        );
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1); //POST提交
    curl_setopt($ch, CURLOPT_POSTFIELDS,$post_data);
    $data =curl_exec($ch);
    curl_close($ch);
    $res= json_decode($data,TRUE);
    //print_r($res);
    $src = $res['result']['data']['url'];
    if($res['result']['msg']=='上传成功'){
    $response = array(
        'code' => 200,
        'data' => array('name'=>$name,'url'=>'https://files.xiami.com/'.$src,'msg'=>'Ok'),
        'msg' =>'成功!' 
        );
    }else{
    $response = array(
        'code' => 500,
        'msg' => '上传失败'
        );
    }
    $response
    = json_encode($response,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    echo $response;
?>
