<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\system\helpers;

use yii\base\InvalidParamException;

/**
 * Class FileHelper
 * @package yuncms\system\helpers
 */
class FileHelper extends \yii\helpers\FileHelper
{
    /**
     * Determine if a file exists.
     *
     * @param  string $path
     * @return bool
     */
    public static function exists($path)
    {
        return file_exists($path);
    }

    /**
     * Write the contents of a file.
     *
     * @param  string $path
     * @param  string $contents
     * @param  bool $lock
     * @return int
     */
    public static function put($path, $contents, $lock = false)
    {
        return file_put_contents($path, $contents, $lock ? LOCK_EX : 0);
    }

    /**
     * Append to a file.
     *
     * @param  string $path
     * @param  string $data
     * @return int
     */
    public function append($path, $data)
    {
        return file_put_contents($path, $data, FILE_APPEND);
    }

    /**
     * Delete a file.
     *
     * @param  string $path
     * @return bool
     */
    public static function delete($path)
    {
        if (static::exists($path)) {
            return @unlink($path);
        }
        return false;
    }

    /**
     * 移动文件到新位置
     *
     * @param  string $path 原始路径
     * @param  string $target 新路径
     * @return void
     */
    public static function move($path, $target)
    {
        return rename($path, $target);
    }

    /**
     * 复制文件到新位置
     *
     * @param  string $path 原始路径
     * @param  string $target 新路径
     * @return void
     */
    public static function copy($path, $target)
    {
        return copy($path, $target);
    }

    /**
     * Get the file type of a given file.
     *
     * @param  string $path
     * @return string
     */
    public static function type($path)
    {
        return filetype($path);
    }

    /**
     * Get the file size of a given file.
     *
     * @param  string $path
     * @return int
     */
    public static function size($path)
    {
        return filesize($path);
    }

    /**
     * 获取文件的最后修改时间
     *
     * @param  string $path
     * @return int
     */
    public static function lastModified($path)
    {
        return filemtime($path);
    }

    /**
     * @param string $path
     * @return string original file base name
     */
    public static function baseName($path)
    {
        // https://github.com/yiisoft/yii2/issues/11012
        return mb_substr(pathinfo('_' . $path, PATHINFO_FILENAME), 1, null, '8bit');
    }

    /**
     * @param string $path
     * @return string file extension
     */
    public static function extension($path)
    {
        return strtolower(pathinfo($path, PATHINFO_EXTENSION));
    }

    /**
     * 获取目录中的所有文件的数组。
     *
     * @param  string $directory
     * @return array
     */
    public static function files($directory)
    {
        $glob = glob($directory . '/*');

        if ($glob === false) return [];
        return array_filter($glob, function ($file) {
            return filetype($file) == 'file';
        });
    }

    /**
     * Empty the specified directory of all files and folders.
     *
     * @param  string $directory
     * @return bool
     */
    public static function cleanDirectory($directory)
    {
        self::removeDirectory($directory);
        return self::createDirectory($directory);
    }

    /**
     * 是否是空文件夹
     * @param string $directory
     * @return bool
     * @throws InvalidParamException
     */
    public static function isEmptyDirectory($directory)
    {
        $isEmpty = true;
        $directory = static::normalizePath($directory);
        $handle = opendir($directory);
        if ($handle === false) {
            throw new InvalidParamException("Unable to open directory: $directory");
        }
        while (($file = readdir($handle)) !== false) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            $isEmpty = false;
            break;
        }
        closedir($handle);
        return $isEmpty;
    }
}