<?php
namespace Clarity\Support\Lang;

use Clarity\Exceptions\FileNotFoundException;

class Lang
{
    private $language;
    private $dir;

    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    public function setLangDir($dir)
    {
        $this->dir = $dir;

        return $this;
    }

    protected function getAttribute($path)
    {
        $exploded = explode('.', $path);

        return [
            'file'     => $this->dir . '/' . $this->language . '/' . $exploded[ 0 ] . '.php',
            'exploded' => $exploded,
        ];
    }

    protected function hasFile($file)
    {
        if (!file_exists($file)) {
            return false;
        }

        return true;
    }

    protected function getDottedFile($file)
    {
        $array = require $file;

        return array_dot($array);
    }

    public function has($path)
    {
        $attribute = $this->getAttribute($path);

        if (!$this->hasFile($attribute[ 'file' ])) {
            return false;
        }

        return true;
    }

    public function get($path, $params = [])
    {
        $attribute = $this->getAttribute($path);

        if (!$this->hasFile($attribute[ 'file' ])) {
            throw new FileNotFoundException("File {$file} not found!");
        }

        # get all the arrays with messages
        $templates = $this->getDottedFile($attribute[ 'file' ]);

        # get the file name
        $file_name = $attribute[ 'exploded' ][ 0 ];

        # get the recursive search of key
        $recursive = substr($path, strlen($file_name) + 1);

        # get the message content
        $content = $templates[ $recursive ];

        if (count($params)) {
            foreach ($params as $key => $val) {
                $content = str_replace('{' . $key . '}', $val, $content);
            }
        }

        return $content;
    }
}
