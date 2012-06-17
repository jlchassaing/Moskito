 <?php



class lcUpgrader
{

    const UPGRADE_FOLDER = "upgrade";

    public function __construct($script = false)
    {
        if (file_exists($script))
        {
            include_once $script;
                $this->upgradeScript = new UpgradeScript();

        }
    }

    public static function getUpgradeListFrom($currentRelease)
    {
        $releaseName = str_replace(".", "-", $currentRelease);
        $filePattern = "#upgrade-".$releaseName."-to-[0-9]{1}-[0-9]{1}\.php#";
        $upgradesList = lcDirTools::dirList(self::UPGRADE_FOLDER, $filePattern);
        return $upgradesList;
    }

    public static function load($script)
    {
         return new self($script);
    }

    public function run()
    {
        $this->upgradeScript->run();
    }


    private $upgradeScript;
}

?>