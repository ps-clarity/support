<?php
/**
 * PhalconSlayer\Framework
 *
 * @copyright 2015-2016 Daison Carino <daison12006013@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://phalconslayer.readme.io
 */

/**
 * @package Clarity
 * @subpackage Clarity\Support\Phalcon\Mvc
 */
namespace Clarity\Support\Phalcon\Mvc;

use Phalcon\Tag;
use Phalcon\Mvc\View as BaseView;
use Clarity\Support\WithMagicMethodTrait;
use Clarity\Exceptions\ViewFileNotFoundException;

class View extends BaseView
{
    use WithMagicMethodTrait;

    const LEVEL_NO_RENDER = 0;
    const LEVEL_ACTION_VIEW = 1;
    const LEVEL_BEFORE_TEMPLATE = 2;
    const LEVEL_LAYOUT = 3;
    const LEVEL_AFTER_TEMPLATE = 4;
    const LEVEL_MAIN_LAYOUT = 5;


    /**
     * Replacing dots into slashes
     *
     * @return string
     */
    protected function changeDotToSlash($path)
    {
        $path = str_replace('.', '/', $path);

        return $path;
    }


    /**
     *
     * @return bool
     */
    public function checkViewPath($path)
    {
        $full_path = di()->get('view')->getViewsDir() . $path;

        $result = glob($full_path . '.*');
        if (!$result) {
            throw new ViewFileNotFoundException(
                'Views file path(' . $full_path . ') not found.'
            );
        }
    }


    /**
     * Assigning the view file path
     *
     * @return mixed
     */
    public function make($path, $records = [])
    {
        $path = $this->changeDotToSlash($path);
        $this->checkViewPath($path);

        return $this->pick($path);
    }


    /**
     * Reconstructing setVar using the popular function 'with'
     *
     * @return mixed
     */
    public function with($key, $val)
    {
        return $this->setVar($key, $val);
    }


    /**
     * Reconstructing setVars into a batch arrays
     *
     * @return mixed
     */
    public function batch($array)
    {
        return $this->setVars($array);
    }

    /**
     * Reconstructing \Phalcon\Tag::setDefault via method chaining
     *
     * @param string $key
     * @param string $val
     *
     * @return mixed
     */
    public function formDefault($key, $val)
    {
        Tag::setDefault($key, $val);

        return $this;
    }

    /**
     * Reconstructing \Phalcon\Tag::setDefaults via method chaining
     *
     * @param array $values
     * @param bool $merge
     *
     * @return mixed
     */
    public function formDefaults($values, $merge = false)
    {
        Tag::setDefaults($values, $merge);

        return $this;
    }


    /**
     * Get the content
     *
     * @return string
     */
    public function take($view, $params = [])
    {
        $view = $this->changeDotToSlash($view);

        return $this->getRender(null, $view, $params);
    }
}
