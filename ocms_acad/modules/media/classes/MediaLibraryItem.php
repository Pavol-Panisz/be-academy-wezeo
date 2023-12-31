<?php namespace Media\Classes;

use Date;
use File;
use Config;
use October\Rain\Filesystem\Definitions as FileDefinitions;

/**
 * MediaLibraryItem represents a file or folder in the Media Library.
 *
 * @package october\media
 * @author Alexey Bobkov, Samuel Georges
 */
class MediaLibraryItem
{
    const TYPE_FILE = 'file';
    const TYPE_FOLDER = 'folder';

    const FILE_TYPE_IMAGE = 'image';
    const FILE_TYPE_VIDEO = 'video';
    const FILE_TYPE_AUDIO = 'audio';
    const FILE_TYPE_DOCUMENT = 'document';

    /**
     * @var string title specifies the item title.
     */
    public $title;

    /**
     * @var string path specifies the item path relative to the Library root.
     */
    public $path;

    /**
     * @var integer size specifies the item size.
     * For files the item size is measured in bytes. For folders it
     * contains the number of files in the folder.
     */
    public $size;

    /**
     * @var integer lastModified contains the last modification time (Unix timestamp).
     */
    public $lastModified;

    /**
     * @var string type specifies the item type.
     */
    public $type;

    /**
     * @var string publicUrl specifies the public URL of the item.
     */
    public $publicUrl;

    /**
     * @var array imageExtensions contains a default list of image files and directories to ignore.
     * Override with config: media.image_extensions
     */
    protected static $imageExtensions;

    /**
     * @var array videoExtensions contains a default list of video files and directories to ignore.
     * Override with config: media.video_extensions
     */
    protected static $videoExtensions;

    /**
     * @var array audioExtensions contains a default list of audio files and directories to ignore.
     * Override with config: media.audio_extensions
     */
    protected static $audioExtensions;

    /**
     * __construct a library item
     * @param string $path
     * @param int $size
     * @param int $lastModified
     * @param string $type
     * @param string $publicUrl
     */
    public function __construct($path, $size, $lastModified, $type, $publicUrl)
    {
        $this->path = $path;
        $this->size = $size;
        $this->lastModified = $lastModified;
        $this->type = $type;
        $this->publicUrl = $publicUrl;
        $this->title = basename($path);
    }

    /**
     * isFile
     * @return bool
     */
    public function isFile()
    {
        return $this->type === self::TYPE_FILE;
    }

    /**
     * getFileType returns the file type by its name. Returns the file type or NULL
     * if the item is a folder.
     * The known file types are: image, video, audio, document
     * @return string
     */
    public function getFileType()
    {
        if (!$this->isFile()) {
            return null;
        }

        if (!self::$imageExtensions) {
            self::$imageExtensions = array_map('strtolower', FileDefinitions::get('image_extensions'));
            self::$videoExtensions = array_map('strtolower', FileDefinitions::get('video_extensions'));
            self::$audioExtensions = array_map('strtolower', FileDefinitions::get('audio_extensions'));
        }

        $extension = strtolower(pathinfo($this->path, PATHINFO_EXTENSION));
        if (!strlen($extension)) {
            return self::FILE_TYPE_DOCUMENT;
        }

        if (in_array($extension, self::$imageExtensions)) {
            return self::FILE_TYPE_IMAGE;
        }

        if (in_array($extension, self::$videoExtensions)) {
            return self::FILE_TYPE_VIDEO;
        }

        if (in_array($extension, self::$audioExtensions)) {
            return self::FILE_TYPE_AUDIO;
        }

        return self::FILE_TYPE_DOCUMENT;
    }

    /**
     * sizeToString returns the item size as string.
     * For file-type items the size is the number of bytes. For folder-type items
     * the size is the number of items contained by the item.
     * @return string
     */
    public function sizeToString()
    {
        return $this->type === self::TYPE_FILE
            ? File::sizeToString($this->size)
            : $this->size.' '.trans('system::lang.media.folder_size_items');
    }

    /**
     * lastModifiedAsString returns the item last modification date as string.
     * @return string Returns the item's last modification date as string.
     */
    public function lastModifiedAsString()
    {
        if (!$date = $this->lastModified) {
            return null;
        }

        return Date::createFromTimestamp($date)->toFormattedDateString();
    }

    /**
     * forgetExtensions resets extensions supplied by config but stored in RAM
     */
    public static function forgetExtensions()
    {
        self::$imageExtensions = null;
    }
}
