<?php
/**
 * Basic class all modules showl extend
 */
class EselModule
{
    public $Esel;
    private $inTemplates;
    public function __construct($Esel,$inTemplates = false)
    {
        $this->Esel = &$Esel;
        $this->inTemplates=$inTemplates;
    }

    private static function calculateHash($directory = '')
    {
        if (empty($directory)) {
            $directory = dirname(__FILE__);
        }
        if (!is_dir($directory)) {
            return false;
        }

        $files = array();
        $dir = dir($directory);

        while (false !== ($file = $dir->read())) {
            if ($file != '.' and $file != '..') {
                if (is_dir($directory.'/'.$file)) {
                    $files[] = self::calculateHash($directory.'/'.$file);
                } else {
                    $files[] = md5_file($directory.'/'.$file);
                }
            }
        }

        $dir->close();

        return md5(implode(SL_SECRET, $files));
    }

    private static function saveHash($data = '', $hash = '')
    {
        if (empty($hash)) {
            $hash = self::calculateHash();
        }
        $dir = SL_CORE.'hash/';
        if (!is_dir($dir)) {
            mkdir($dir, 0755);
        }
        $path = $dir.$hash;
        file_put_contents($path, $data);

        return SL_CORE.'hash/'.$hash;
    }

    public static function setSafe($moduleName)
    {
        $hash = self::calculateHash(SL_MODULES.$moduleName.'/');

        return self::saveHash(md5($moduleName.SL_SECRET.$hash), $hash);
    }

    public static function setUnsafe($moduleName)
    {
        $hash = self::calculateHash(SL_MODULES.$moduleName.'/');
        unlink(SL_CORE.'hash/'.$hash);
    }

    public static function isSafe($moduleName)
    {
        $hash = self::calculateHash(SL_MODULES.$moduleName.'/');
        if ((!$hash)||(!file_exists(SL_CORE.'hash/'.$hash))) {
            throw new Exception($moduleName.' is not installed');
        }
            $result = file_get_contents(SL_CORE.'hash/'.$hash);
            return md5($moduleName.SL_SECRET.$hash) === $result;

    }
}
