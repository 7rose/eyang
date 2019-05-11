<?php

namespace App\Helpers;

use Carbon\Carbon;
use Image;
use Storage;

use App\Product;
use App\Order;
use App\Conf;
use App\Helpers\Info;

/**
 * 门店
 *
 */
class Picker
{
    private $hours; # 新品时限

    function __construct()
    {
        $this->hours = 6;
    }

    /**
     * 新品
     *
     */
    public function fresh($id)
    {
        $target = Product::findOrFail($id);
        $end = $target->updated_at->addHours($this->hours);

        return $end->gt(now());
    }

    /**
     * 过期
     *
     */
    public function ok($record)
    {
        $end = $record->created_at->startOfDay()->addHours(21)->addMinutes(30);

        return $end->gt(now()) ? $end->diffForHumans() : false;
    }

    /**
     * 报备但未审批
     *
     */
    public function submit($record)
    {
        return isset($record->bb) && !$record->bb->check;
    }

    /**
     * 报备但未审批
     *
     */
    public function check($record)
    {
        return isset($record->bb) && $record->bb->check;
    }

    /**
     * 报备结果
     *
     */
    public function submitResault($record)
    {
        return isset($record->bb) && $record->bb->success;
    }

    /**
     * 审批结果
     *
     */
    public function checkResault($record)
    {
        return isset($record->bb) && $record->bb->resault;
    }

    /**
     * 轮播图片
     *
     */
    public function slide()
    {
        $info = new Info;

        $record = Conf::where('key', 'slide')->first();

        return $record && $record->text && !$info->lackProduct($record->text) ? ['img' => $record->val, 'id' => intval($record->text), 'conf_id' => $record->id] : false;
    }

    /**
     * 轮播图片: 设置
     *
     */
    public function setSlide($product_id)
    {
        $product = Product::findOrFail($product_id);

        $this->setImage($product);

        return $this->slide();
    }

    /**
     * 轮播图片: 删除
     *
     */
    public function removeSlide()
    {
        $array = $this->slide();

        if($array) {
            if(Storage::has($array['img'])) Storage::delete([$array['img']]);
            $conf = Conf::findOrFail($array['conf_id']);
            $conf->update(['text' => null]);
        }
    }

    /**
     * 轮播图片: 删除产品时检测删除
     *
     */
    public function clearIfSlide($product_id)
    {
        $array = $this->slide();

        return $array && $array['id'] == $product_id ? $this->removeSlide() : null;
    }

    /**
     * 轮播图片: 设置 - 图片合成
     *
     */
    private function setImage($product)
    {
        // 清理
        $this->removeSlide();

        $slide_back = 'img/slide_back.png';
        $slide_frame = 'img/slide_frame.png';
        $slide_icon = $product->img;

        $new_slide = 'storage/app/img/'.$product->id.'-slide-'.time().'.jpg';

        $real_slide = 'storage/'.$new_slide;

        $image = Image::make($slide_back)
                        ->insert($slide_icon, 'top-left', 180, 60)
                        ->insert($slide_frame, 'top-left', 180, 60)
                        ->save($real_slide, 85);

        Conf::updateOrInsert(
            ['key' => 'slide'],
            ['val' => $new_slide, 'text' => $product->id]
        );
    }



    /**
     * 
     *
     */
}














