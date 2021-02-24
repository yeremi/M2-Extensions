<?php
/**
 * https://magento.stackexchange.com/questions/187302/resize-and-crop-to-set-width-height-without-stretching?rq=1
 */

namespace Klloom\Image\Model\Product;

class Image extends \Magento\Catalog\Model\Product\Image
{

    /**
     * @see \Magento\Framework\Image\Adapter\AbstractAdapter
     * @return $this
     */
    public function resize()
    {
        if (is_null($this->getWidth()) && is_null($this->getHeight())) {
            return $this;
        }
        // if given both dimensions crop to take up max of both dimensions
        if (!is_null($this->getWidth()) && !is_null($this->getHeight())) {
            $new_width = $this->_width;
            $new_height = $this->_height;

            $this->getImageProcessor();

            $orig_width = $this->_processor->getOriginalWidth();
            $orig_height = $this->_processor->getOriginalHeight();

            $new_ratio = $new_width / $new_height;

            $orig_ratio = $orig_width / $orig_height;

            // if original has wider dimensions than new dimensions
            if ($orig_ratio > $new_ratio) {
                $temp_width = round($orig_ratio * $new_height);

                $tmp_constrain = $this->_constrainOnly;
                $this->setConstrainOnly(false);
                $this->getImageProcessor()->resize($temp_width, $new_height);
                $this->setConstrainOnly($tmp_constrain);

                $crop_amount = floor(($temp_width - $new_width) / 2);
                $crop_remainder = ($temp_width - $new_width) % 2;

                $this->_processor->crop(0, $crop_amount + $crop_remainder, $crop_amount, 0);


                // if original has taller dimensions than new dimensions
            } else {
                $temp_height = round((1 / $orig_ratio) * $new_width);

                $tmp_constrain = $this->_constrainOnly;
                $this->setConstrainOnly(false);
                $this->getImageProcessor()->resize($new_width, $temp_height);
                $this->setConstrainOnly($tmp_constrain);

                $crop_amount = floor(($temp_height - $new_height) / 2);
                $crop_remainder = ($temp_height - $new_height) % 2;

                $this->_processor->crop($crop_amount + $crop_remainder, 0, 0, $crop_amount);

            }

        } else {
            $this->getImageProcessor()->resize($this->_width, $this->_height);
        }
        return $this;
    }


}