<?php

namespace App\Handles;

use Image;

class ImageUploadHandle
{
    protected $allowed_ext = ['jpg', 'png', 'gif', 'jpeg'];

    /*
     * file: 文件内容
     * folder: 存储的文件夹
     * file_prefix: 是为了组合文件名进行区分,加前缀是为了增加辨析度，前缀可以是相关数据模型的ID值如：1_1493521050_7BVc9v9ujP.png
     * 1.先获取文件位置
     * 这个位置其实分成三部分：1.实际在linux中的路径，也就是public文件夹的路径
     * 2.在项目中的存储路径 , public 文件夹所在路径， 文件所在路径， 文件名
     * 3.文件名
     * 2.移动
     * move(第一个是要移动的文件夹路径，第二个是文件名)
     * */
    public function save($file, $folder, $file_prefix, $max_width = false )
    {
        $folder_path = 'images/'.$folder.'/'.date('Ym', time());
        $true_path = public_path().'/'.$folder_path;

        $ext = strtolower($file->getClientOriginalExtension())?:'png';
        $filename = $file_prefix.'_'.time().'_'.str_random(10).'.'.$ext;


        if (!in_array($ext, $this->allowed_ext)) {
            return false;
        }

        $file->move($true_path, $filename);

        if ($max_width) {
            $this->reduceSize($true_path.'/'.$filename, $max_width);
        }

        return [
            'path'=>config('app.url').'/'.$folder_path.'/'.$filename
        ];
    }

    public function reduceSize($file_path, $max_width)
    {
        // 先实例化，传参是文件的磁盘物理路径
        $image = Image::make($file_path);

        // 进行大小调整的操作
        $image->resize($max_width, null, function ($constraint) {

            // 设定宽度是 $max_width，高度等比例双方缩放
            $constraint->aspectRatio();

            // 防止裁图时图片尺寸变大
            $constraint->upsize();
        });

        // 对图片修改后进行保存
        $image->save();
    }
}