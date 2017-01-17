<?php

class languageCodeManager
{
    private static $arrayISO639_1 = null;
    private static $arrayISO639_T = null;
    private static $arrayBorhanName = null;

    const ISO639_1 = 0; //lowercase
    const ISO639_T = 1;
    const ISO639_B = 2;
    const ISO_NAME = 3;
    const ISO_NATIVE_NAME = 4;
    const BORHAN_NAME = 5;


    public static function loadLanguageCodeMap()
    {
        $cacheFileName = kConf::get("cache_root_path") . "/infra/languageCodeMapCache.php";
        if(self::isAlreadyLoaded())
            return;
        else
        {
            $max_include_retries = 10;
            $cacheFileCode = null;
            while ((!@include_once($cacheFileName)) and $max_include_retries--) {
                if (!$cacheFileCode) {
                    $cacheFileCode = self::generateCacheFile();
                    if (!$cacheFileCode)
                        return;
                }

                $cacheDir = dirname($cacheFileName);
                if (!is_dir($cacheDir)) {
                    @mkdir($cacheDir);
                    chmod($cacheDir, 0775);
                }
                kFile::safeFilePutContents($cacheFileName, $cacheFileCode, 0644);
            }
            if (!file_exists($cacheFileName)) {
                throw new Exception("Could not include cached code file - {$cacheFileName}");
            }
        }
    }

    private static function isAlreadyLoaded()
    {
        return isset(self::$arrayISO639_1) && isset(self::$arrayISO639_T) && isset(self::$arrayBorhanName);
    }

    public static function getObjectFromTwoCode($codeUppercase)
    {
        if(!self::isAlreadyLoaded())
            self::loadLanguageCodeMap();
        return isset(self::$arrayISO639_1[$codeUppercase]) ? self::$arrayISO639_1[$codeUppercase] : null;
    }

    public static function getObjectFromThreeCode($codeT)
    {
        if(!self::isAlreadyLoaded())
            self::loadLanguageCodeMap();
        $val = isset(self::$arrayISO639_T[$codeT]) ? self::$arrayISO639_T[$codeT] : null;
        return self::getObjectFromTwoCode($val);
    }

    public static function getObjectFromBorhanName($borhanName)
    {
        if(!self::isAlreadyLoaded())
            self::loadLanguageCodeMap();
        $val = isset(self::$arrayBorhanName[$borhanName]) ? self::$arrayBorhanName[$borhanName] : null;
        return self::getObjectFromTwoCode($val);
    }

    public static function getTwoCodeFromBorhanName($borhanName)
    {
        if(!self::isAlreadyLoaded())
            self::loadLanguageCodeMap();
        return isset(self::$arrayBorhanName[$borhanName]) ? self::$arrayBorhanName[$borhanName] : null;
    }

    /**
     * @param $language - the language to search
     * @return the 2 code key or $defaultCode if not known
     */
    public static function getLanguageKey($language,$langaugeKey = null)
    {
        if(!self::isAlreadyLoaded())
            self::loadLanguageCodeMap();

        if(isset(self::$arrayISO639_1[$language]))
            return $language;

        if(isset(self::$arrayISO639_T[$language]))
            return self::$arrayISO639_T[$language];

        if(isset(self::$arrayBorhanName[$language]))
            return self::$arrayBorhanName[$language];

        else return $langaugeKey;
    }

    /**
     * @param $arrayISO639_1
     * @param $arrayISO639_T
     * @param $arrayBorhanName
     * @param $ISO639_1Upper - upper case language code as in ISO 639-1
     * @param $ISO639_1Lower - lower case language code as in ISO 639-1
     * @param $ISO639_T - lower case three letters language code as in ISO 639-2/T  - if two code is not official then the 3 code is made up
     * @param $ISO639_B - lower case three letters language code as in ISO 639-2/B  - if two code is not official then the 3 code is made up
     * @param $languageName - language name
     * @param $nativeName - native language name
     * @param $borhanName - borhan language name as in BorhanLanguage, if the language is not defined in borhan then $borhanName is the same as $languageName
     */
    private static function addLanguageToArrays(&$arrayISO639_1 , &$arrayISO639_T , &$arrayBorhanName,
                                                $ISO639_1Upper ,$ISO639_1Lower,$ISO639_T,$ISO639_B,$languageName,$nativeName,$borhanName)
    {
        $arrayISO639_1[$ISO639_1Upper] = array($ISO639_1Lower,$ISO639_T,$ISO639_B,$languageName,$nativeName,$borhanName);
        $arrayISO639_T[$ISO639_T] = $ISO639_1Upper;
        $arrayBorhanName[$borhanName] = $ISO639_1Upper;
    }

    private static function generateCacheFile()
    {
        $tmpArrTwoCode = array(); //$arrayISO639_1
        $tmpArrThreeCodeT = array(); //$arrayISO639_T
        $tmpArrBorhanName = array(); //$arrayBorhanName

        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'AB', "ab", "abk", "abk", "Abkhaz", "\xd0\xb0\xd2\xa7\xd1\x81\xd1\x83\xd0\xb0 \xd0\xb1\xd1\x8b\xd0\xb7\xd1\x88\xd3\x99\xd0\xb0, \xd0\xb0\xd2\xa7\xd1\x81\xd1\x88\xd3\x99\xd0\xb0",'Abkhazian');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'AA', "aa", "aar", "aar", "Afar", "Afaraf",'Afar');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'AF', "af", "afr", "afr", "Afrikaans", "Afrikaans",'Afrikaans');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'AK', "ak", "aka", "aka", "Akan", "Akan",'Akan');  //not yet defined in borhan
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'SQ', "sq", "sqi", "alb", "Albanian", "Shqip",'Albanian');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'AM', "am", "amh", "amh", "Amharic", "\xe1\x8a\xa0\xe1\x88\x9b\xe1\x88\xad\xe1\x8a\x9b",'Amharic');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'AR', "ar", "ara", "ara", "Arabic", "\xd8\xa7\xd9\x84\xd8\xb9\xd8\xb1\xd8\xa8\xd9\x8a\xd8\xa9",'Arabic');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'AN', "an", "arg", "arg", "Aragonese", "aragon\xc3\xa9s",'Aragonese');//not yet defined in borhan
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'HY', "hy", "hye", "arm", "Armenian", "\xd5\x80\xd5\xa1\xd5\xb5\xd5\xa5\xd6\x80\xd5\xa5\xd5\xb6",'Armenian');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'AS_', "as", "asm", "asm", "Assamese", "\xe0\xa6\x85\xe0\xa6\xb8\xe0\xa6\xae\xe0\xa7\x80\xe0\xa6\xaf\xe0\xa6\xbc\xe0\xa6\xbe",'Assamese');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'AV', "av", "ava", "ava", "Avaric", "\xd0\xb0\xd0\xb2\xd0\xb0\xd1\x80 \xd0\xbc\xd0\xb0\xd1\x86\xd3\x80, \xd0\xbc\xd0\xb0\xd0\xb3\xd3\x80\xd0\xb0\xd1\x80\xd1\x83\xd0\xbb \xd0\xbc\xd0\xb0\xd1\x86\xd3\x80",'Avaric');//not  yet defined in borhan
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'AE', "ae", "ave", "ave", "Avestan", "avesta",'Avestan');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'AY', "ay", "aym", "aym", "Aymara", "aymar aru",'Aymara');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'AZ', "az", "aze", "aze", "Azerbaijani", "az\xc9\x99rbaycan dili",'Azerbaijani');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'BM', "bm", "bam", "bam", "Bambara", "bamanankan",'Bambara'); // not yet defined in borhan
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'BA', "ba", "bak", "bak", "Bashkir", "\xd0\xb1\xd0\xb0\xd1\x88\xd2\xa1\xd0\xbe\xd1\x80\xd1\x82 \xd1\x82\xd0\xb5\xd0\xbb\xd0\xb5",'Bashkir');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'EU', "eu", "eus", "baq", "Basque", "euskara, euskera",'Basque');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'BE', "be", "bel", "bel", "Belarusian", "\xd0\xb1\xd0\xb5\xd0\xbb\xd0\xb0\xd1\x80\xd1\x83\xd1\x81\xd0\xba\xd0\xb0\xd1\x8f \xd0\xbc\xd0\xbe\xd0\xb2\xd0\xb0",'Byelorussian (Belarusian)');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'BN', "bn", "ben", "ben", "Bengali, Bangla", "\xe0\xa6\xac\xe0\xa6\xbe\xe0\xa6\x82\xe0\xa6\xb2\xe0\xa6\xbe",'Bengali (Bangla)');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'BH', "bh", "bih", "bih", "Bihari", "\xe0\xa4\xad\xe0\xa5\x8b\xe0\xa4\x9c\xe0\xa4\xaa\xe0\xa5\x81\xe0\xa4\xb0\xe0\xa5\x80",'Bihari');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'BI', "bi", "bis", "bis", "Bislama", "Bislama",'Bislama');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'BS', "bs", "bos", "bos", "Bosnian", "bosanski jezik",'Bosnian');//not yet defined in borhan
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'BR', "br", "bre", "bre", "Breton", "brezhoneg",'Breton');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'BG', "bg", "bul", "bul", "Bulgarian", "\xd0\xb1\xd1\x8a\xd0\xbb\xd0\xb3\xd0\xb0\xd1\x80\xd1\x81\xd0\xba\xd0\xb8 \xd0\xb5\xd0\xb7\xd0\xb8\xd0\xba",'Bulgarian');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'MY', "my", "mya", "bur", "Burmese", "\xe1\x80\x97\xe1\x80\x99\xe1\x80\xac\xe1\x80\x85\xe1\x80\xac",'Burmese');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'CA', "ca", "cat", "cat", "Catalan", "catal\xc3\xa0",'Catalan');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'CH', "ch", "cha", "cha", "Chamorro", "Chamoru",'Chamorro');//not yet defined in borhan
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'CE', "ce", "che", "che", "Chechen", "\xd0\xbd\xd0\xbe\xd1\x85\xd1\x87\xd0\xb8\xd0\xb9\xd0\xbd \xd0\xbc\xd0\xbe\xd1\x82\xd1\x82",'Chechen');//not yet defined in borhan
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'NY', "ny", "nya", "nya", "Chichewa, Chewa, Nyanja", "chiChe\xc5\xb5",'Chichewa'); //not yet defined in borhan
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'ZH', "zh", "zho", "chi", "Chinese", "\xe4\xb8\xad\xe6\x96\x87 (Zh\xc5\x8dngw\xc3\xa9n), \xe6\xb1\x89\xe8\xaf\xad, \xe6\xbc\xa2\xe8\xaa\x9e",'Chinese');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'CV', "cv", "chv", "chv", "Chuvash", "\xd1\x87\xd3\x91\xd0\xb2\xd0\xb0\xd1\x88 \xd1\x87\xd3\x97\xd0\xbb\xd1\x85\xd0\xb8",'Chuvash');//not yet defined in borhan
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'KW', "kw", "cor", "cor", "Cornish", "Kernewek",'Cornish');//not yet defined in borhan
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'CO', "co", "cos", "cos", "Corsican", "corsu, lingua corsa",'Corsican');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'CR', "cr", "cre", "cre", "Cree", "\xe1\x93\x80\xe1\x90\xa6\xe1\x90\x83\xe1\x94\xad\xe1\x90\x8d\xe1\x90\x8f\xe1\x90\xa3",'Cree');//not yet defined in borhan
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'HR', "hr", "hrv", "hrv", "Croatian", "hrvatski jezik",'Croatian');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'CS', "cs", "ces", "cze", "Czech", "\xc4\x8d" ,'Czech');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'DA', "da", "dan", "dan", "Danish", "dansk",'Danish');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'DV', "dv", "div", "div", "Divehi, Dhivehi, Maldivian", "\xde\x8b\xde\xa8\xde\x88\xde\xac\xde\x80\xde\xa8",'Divehi, Dhivehi, Maldivian');//not yet defined in borhan
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'NL', "nl", "nld", "dut", "Dutch", "Nederlands, Vlaams",'Dutch');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'DZ', "dz", "dzo", "dzo", "Dzongkha", "\xe0\xbd\xa2\xe0\xbe\xab\xe0\xbd\xbc\xe0\xbd\x84\xe0\xbc\x8b\xe0\xbd\x81",'Bhutani');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'EN', "en", "eng", "eng", "English", "English",'English');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'EO', "eo", "epo", "epo", "Esperanto", "Esperanto",'Esperanto');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'ET', "et", "est", "est", "Estonian", "eesti, eesti keel",'Estonian');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'EE', "ee", "ewe", "ewe", "Ewe", "E\xca\x8b",'Ewe'); //not yet defined in borhan
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'FO', "fo", "fao", "fao", "Faroese", "f\xc3\xb8royskt",'Faeroese');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'FJ', "fj", "fij", "fij", "Fijian", "vosa Vakaviti",'Fiji');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'FI', "fi", "fin", "fin", "Finnish", "suomi, suomen kieli",'Finnish');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'FR', "fr", "fra", "fre", "French", "fran\xc3\xa7",'French');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'FF', "ff", "ful", "ful", "Fula, Fulah, Pulaar, Pular", "Fulfulde, Pulaar, Pular",'Fula, Fulah, Pulaar, Pular');//not yet defined in borhan
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'GL', "gl", "glg", "glg", "Galician", "galego",'Galician');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'KA', "ka", "kat", "geo", "Georgian", "\xe1\x83\xa5\xe1\x83\x90\xe1\x83\xa0\xe1\x83\x97\xe1\x83\xa3\xe1\x83\x9a\xe1\x83\x98",'Georgian');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'DE', "de", "deu", "ger", "German", "Deutsch",'German');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'EL', "el", "ell", "gre", "Greek (modern)", "\xce\xb5\xce\xbb\xce\xbb\xce\xb7\xce\xbd\xce\xb9\xce\xba\xce\xac",'Greek');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'GN', "gn", "grn", "grn", "Guaran\xc3\xad", "Ava\xc3\xb1",'Guarani');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'GU', "gu", "guj", "guj", "Gujarati", "\xe0\xaa\x97\xe0\xab\x81\xe0\xaa\x9c\xe0\xaa\xb0\xe0\xaa\xbe\xe0\xaa\xa4\xe0\xab\x80",'Gujarati');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'HT', "ht", "hat", "hat", "Haitian, Haitian Creole", "Krey\xc3\xb2l ayisyen",'Haitian, Haitian Creole');//not yet defined in borhan
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'HA', "ha", "hau", "hau", "Hausa", "(Hausa) \xd9\x87\xd9\x8e\xd9\x88\xd9\x8f\xd8\xb3\xd9\x8e",'Hausa');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'HE', "he", "heb", "heb", "Hebrew (modern)", "\xd7\xa2\xd7\x91\xd7\xa8\xd7\x99\xd7\xaa",'Hebrew');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'HZ', "hz", "her", "her", "Herero", "Otjiherero",'Herero');//not yet defined in borhan
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'HI', "hi", "hin", "hin", "Hindi", "\xe0\xa4\xb9\xe0\xa4\xbf\xe0\xa4\xa8\xe0\xa5\x8d\xe0\xa4\xa6\xe0\xa5\x80, \xe0\xa4\xb9\xe0\xa4\xbf\xe0\xa4\x82\xe0\xa4\xa6\xe0\xa5\x80",'Hindi');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'HO', "ho", "hmo", "hmo", "Hiri Motu", "Hiri Motu",'Hiri Motu');//not yet defined in borhan
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'HU', "hu", "hun", "hun", "Hungarian", "magyar",'Hungarian');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'IA', "ia", "ina", "ina", "Interlingua", "Interlingua",'Interlingua');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'ID', "id", "ind", "ind", "Indonesian", "Bahasa Indonesia",'Indonesian');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'IE', "ie", "ile", "ile", "Interlingue", "Originally called Occidental; then Interlingue after WWII",'Interlingue');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'GA', "ga", "gle", "gle", "Irish", "Gaeilge",'Irish');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'IG', "ig", "ibo", "ibo", "Igbo", "As\xe1\xbb\xa5s\xe1\xbb\xa5 Igbo",'Igbo');//not yet defined in borhan
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'IK', "ik", "ipk", "ipk", "Inupiaq", "I\xc3\xb1upiaq, I\xc3\xb1upiatun",'Inupiak');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'IO', "io", "ido", "ido", "Ido", "Ido",'Ido');//not yet defined in borhan
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'IS', "is", "isl", "ice", "Icelandic", "\xc3\x8dslenska",'Icelandic');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'IT', "it", "ita", "ita", "Italian", "italiano",'Italian');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'IU', "iu", "iku", "iku", "Inuktitut", "\xe1\x90\x83\xe1\x93\x84\xe1\x92\x83\xe1\x91\x8e\xe1\x91\x90\xe1\x91\xa6",'Inuktitut');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'JA', "ja", "jpn", "jpn", "Japanese", "\xe6\x97\xa5\xe6\x9c\xac\xe8\xaa\x9e (\xe3\x81\xab\xe3\x81\xbb\xe3\x82\x93\xe3\x81\x94)",'Japanese');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'JV', "jv", "jav", "jav", "Javanese", "\xea\xa6\xa7\xea\xa6\xb1\xea\xa6\x97\xea\xa6\xae",'Javanese');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'KL', "kl", "kal", "kal", "Kalaallisut, Greenlandic", "kalaallisut, kalaallit oqaasii",'Greenlandic');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'KN', "kn", "kan", "kan", "Kannada", "\xe0\xb2\x95\xe0\xb2\xa8\xe0\xb3\x8d\xe0\xb2\xa8\xe0\xb2\xa1",'Kannada');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'KR', "kr", "kau", "kau", "Kanuri", "Kanuri",'Kanuri');//not yet defined in borhan
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'KS', "ks", "kas", "kas", "Kashmiri", "\xe0\xa4\x95\xe0\xa4\xb6\xe0\xa5\x8d\xe0\xa4\xae\xe0\xa5\x80\xe0\xa4\xb0\xe0\xa5\x80, \xd9\x83\xd8\xb4\xd9\x85\xd9\x8a\xd8\xb1\xd9\x8a\xe2\x80\x8e",'Kashmiri');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'KK', "kk", "kaz", "kaz", "Kazakh", "\xd2\x9b\xd0\xb0\xd0\xb7\xd0\xb0\xd2\x9b \xd1\x82\xd1\x96\xd0\xbb\xd1\x96",'Kazakh');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'KM', "km", "khm", "khm", "Khmer", "\xe1\x9e\x81\xe1\x9f\x92\xe1\x9e\x98\xe1\x9f\x82\xe1\x9e\x9a, \xe1\x9e\x81\xe1\x9f\x81\xe1\x9e\x98\xe1\x9e\x9a\xe1\x9e\x97\xe1\x9e\xb6\xe1\x9e\x9f\xe1\x9e\xb6, \xe1\x9e\x97\xe1\x9e\xb6\xe1\x9e\x9f\xe1\x9e\xb6\xe1\x9e\x81\xe1\x9f\x92\xe1\x9e\x98\xe1\x9f\x82\xe1\x9e\x9a",'Cambodian');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'KI', "ki", "kik", "kik", "Kikuyu, Gikuyu", "G\xc4\xa9k\xc5\xa9y\xc5\xa9",'Kikuyu, Gikuyu');//not yet defined in borhan
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'RW', "rw", "kin", "kin", "Kinyarwanda", "Ikinyarwanda",'Kinyarwanda (Ruanda)');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'KY', "ky", "kir", "kir", "Kyrgyz", "\xd0\x9a\xd1\x8b\xd1\x80\xd0\xb3\xd1\x8b\xd0\xb7\xd1\x87\xd0\xb0, \xd0\x9a\xd1\x8b\xd1\x80\xd0\xb3\xd1\x8b\xd0\xb7 \xd1\x82\xd0\xb8\xd0\xbb\xd0\xb8",'Kirghiz');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'KV', "kv", "kom", "kom", "Komi", "\xd0\xba\xd0\xbe\xd0\xbc\xd0\xb8 \xd0\xba\xd1\x8b\xd0\xb2",'Komi');//not yet defined in borhan
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'KG', "kg", "kon", "kon", "Kongo", "Kikongo",'Kongo');//not yet defined in borhan
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'KO', "ko", "kor", "kor", "Korean", "\xed\x95\x9c\xea\xb5\xad\xec\x96\xb4, \xec\xa1\xb0\xec\x84\xa0\xec\x96\xb4",'Korean');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'KU', "ku", "kur", "kur", "Kurdish", "Kurd\xc3\xae, \xd9\x83\xd9\x88\xd8\xb1\xd8\xaf\xdb\x8c\xe2\x80\x8e",'Kurdish');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'KJ', "kj", "kua", "kua", "Kwanyama, Kuanyama", "Kuanyama".'Kwanyama, Kuanyama','Kwanyama, Kuanyama');//not yet defined in borhan
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'LA', "la", "lat", "lat", "Latin", "latine, lingua latina",'Latin');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'LB', "lb", "ltz", "ltz", "Luxembourgish, Letzeburgesch", "L\xc3\xabtzebuergesch",'Luxembourgish, Letzeburgesch');//not yet defined in borhan
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'LG', "lg", "lug", "lug", "Ganda", "Luganda",'Ganda');//not yet defined in borhan
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'LI', "li", "lim", "lim", "Limburgish, Limburgan, Limburger", "Limburgs",'Limburgish ( Limburger)');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'LN', "ln", "lin", "lin", "Lingala", "Ling\xc3\xa1la",'Lingala');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'LO', "lo", "lao", "lao", "Lao", "\xe0\xba\x9e\xe0\xba\xb2\xe0\xba\xaa\xe0\xba\xb2\xe0\xba\xa5\xe0\xba\xb2\xe0\xba\xa7",'Laothian');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'LT', "lt", "lit", "lit", "Lithuanian", "lietuvi\xc5\xb3 kalba",'Lithuanian');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'LU', "lu", "lub", "lub", "Luba-Katanga", "Tshiluba",'Luba-Katanga');//not yet defined in borhan
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'LV', "lv", "lav", "lav", "Latvian", "latvie\xc5\xa1u valoda",'Latvian (Lettish)');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'GV', "gv", "glv", "glv", "Manx", "Gaelg, Gailck",'Gaelic (Manx)');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'MK', "mk", "mkd", "mac", "Macedonian", "\xd0\xbc\xd0\xb0\xd0\xba\xd0\xb5\xd0\xb4\xd0\xbe\xd0\xbd\xd1\x81\xd0\xba\xd0\xb8 \xd1\x98\xd0\xb0\xd0\xb7\xd0\xb8\xd0\xba",'Macedonian');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'MG', "mg", "mlg", "mlg", "Malagasy", "fiteny malagasy",'Malagasy');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'MS', "ms", "msa", "may", "Malay", "bahasa Melayu, \xd8\xa8\xd9\x87\xd8\xa7\xd8\xb3 \xd9\x85\xd9\x84\xd8\xa7\xd9\x8a\xd9\x88\xe2\x80\x8e",'Malay');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'ML', "ml", "mal", "mal", "Malayalam", "\xe0\xb4\xae\xe0\xb4\xb2\xe0\xb4\xaf\xe0\xb4\xbe\xe0\xb4\xb3\xe0\xb4\x82",'Malayalam');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'MT', "mt", "mlt", "mlt", "Maltese", "Malti",'Maltese');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'MI', "mi", "mri", "mao", "M\xc4\x81ori", "te reo M\xc4\x81ori",'Maori');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'MR', "mr", "mar", "mar", "Marathi (Mar\xc4\x81\xe1\xb9\xadh\xc4\xab)", "\xe0\xa4\xae\xe0\xa4\xb0\xe0\xa4\xbe\xe0\xa4\xa0\xe0\xa5\x80",'Marathi');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'MH', "mh", "mah", "mah", "Marshallese", "Kajin M\xcc\xa7",'Marshallese');//not yet defined in borhan
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'MN', "mn", "mon", "mon", "Mongolian", "\xd0\x9c\xd0\xbe\xd0\xbd\xd0\xb3\xd0\xbe\xd0\xbb \xd1\x85\xd1\x8d\xd0\xbb",'Mongolian');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'NA', "na", "nau", "nau", "Nauruan", "Dorerin Naoero",'Nauru');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'NV', "nv", "nav", "nav", "Navajo, Navaho", "Din\xc3\xa9 bizaad",'Navajo, Navaho');//not yet defined in borhan
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'ND', "nd", "nde", "nde", "Northern Ndebele", "isiNdebele",'Northern Ndebele');//not yet defined in borhan
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'NE', "ne", "nep", "nep", "Nepali", "\xe0\xa4\xa8\xe0\xa5\x87\xe0\xa4\xaa\xe0\xa4\xbe\xe0\xa4\xb2\xe0\xa5\x80",'Nepali');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'NG', "ng", "ndo", "ndo", "Ndonga", "Owambo",'Ndonga');//not yet defined in borhan
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'NB', "nb", "nob", "nob", "Norwegian Bokm\xc3\xa5l", "Norsk bokm\xc3\xa5l","Norwegian Bokm\xc3\xa5l");//not  yet defined in borhan
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'NN', "nn", "nno", "nno", "Norwegian Nynorsk", "Norsk nynorsk",'Norwegian Nynorsk');//not yet defined in borhan
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'NO', "no", "nor", "nor", "Norwegian", "Norsk",'Norwegian');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'II', "ii", "iii", "iii", "Nuosu", "\xea\x86\x88\xea\x8c\xa0\xea\x92\xbf Nuosuhxop",'Nuosu');//not  yet defined in borhan
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'NR', "nr", "nbl", "nbl", "Southern Ndebele", "isiNdebele",'Southern Ndebele');//not yet defined in borhan
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'OC', "oc", "oci", "oci", "Occitan", "occitan, lenga d'\xc3\xb2",'Occitan');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'OJ', "oj", "oji", "oji", "Ojibwe, Ojibwa", "\xe1\x90\x8a\xe1\x93\x82\xe1\x94\x91\xe1\x93\x88\xe1\x90\xaf\xe1\x92\xa7\xe1\x90\x8e\xe1\x93\x90",'Ojibwe, Ojibwa');//not yet defined in borhan
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'CU', "cu", "chu", "chu", "Old Church Slavonic, Church Slavonic, Old Bulgarian", "\xd1\xa9\xd0\xb7\xd1\x8b\xd0\xba\xd1\x8a \xd1\x81\xd0\xbb\xd0\xbe\xd0\xb2\xd1\xa3\xd0\xbd\xd1\x8c\xd1\x81\xd0\xba\xd1\x8a",'Old Church Slavonic, Church Slavonic, Old Bulgarian');//not  yet defined in borhan
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'OM', "om", "orm", "orm", "Oromo", "Afaan Oromoo",'Oromo (Afan, Galla)');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'OR_', "or", "ori", "ori", "Oriya", "\xe0\xac\x93\xe0\xac\xa1\xe0\xac\xbc\xe0\xac\xbf\xe0\xac\x86",'Oriya');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'OS', "os", "oss", "oss", "Ossetian, Ossetic", "\xd0\xb8\xd1\x80\xd0\xbe\xd0\xbd \xc3\xa6\xd0\xb2\xd0\xb7\xd0\xb0\xd0\xb3",'Ossetian, Ossetic');//not yet defined in borhan
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'PA', "pa", "pan", "pan", "Panjabi, Punjabi", "\xe0\xa8\xaa\xe0\xa9\xb0\xe0\xa8\x9c\xe0\xa8\xbe\xe0\xa8\xac\xe0\xa9\x80, \xd9\xbe\xd9\x86\xd8\xac\xd8\xa7\xd8\xa8\xdb\x8c\xe2\x80\x8e",'Punjabi');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'PI', "pi", "pli", "pli", "P\xc4\x81li", "\xe0\xa4\xaa\xe0\xa4\xbe\xe0\xa4\xb4\xe0\xa4\xbf","P\xc4\x81li");//not yet defined in borhan
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'FA', "fa", "fas", "per", "Persian (Farsi)", "\xd9\x81\xd8\xa7\xd8\xb1\xd8\xb3\xdb\x8c",'Farsi');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'PL', "pl", "pol", "pol", "Polish", "j\xc4\x99zyk polski, polszczyzna",'Polish');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'PS', "ps", "pus", "pus", "Pashto, Pushto", "\xd9\xbe\xda\x9a\xd8\xaa\xd9\x88",'Pashto (Pushto)');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'PT', "pt", "por", "por", "Portuguese", "portugu\xc3\xaas",'Portuguese');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'QU', "qu", "que", "que", "Quechua", "Runa Simi, Kichwa",'Quechua');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'RM', "rm", "roh", "roh", "Romansh", "rumantsch grischun",'Rhaeto-Romance');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'RN', "rn", "run", "run", "Kirundi", "Ikirundi",'Kirundi (Rundi)');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'RO', "ro", "ron", "rum", "Romanian", "limba rom\xc3\xa2n\xc4\x83",'Romanian');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'RU', "ru", "rus", "rus", "Russian", "\xd0\xa0\xd1\x83\xd1\x81\xd1\x81\xd0\xba\xd0\xb8\xd0\xb9",'Russian');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'SA', "sa", "san", "san", "Sanskrit (Sa\xe1\xb9\x81sk\xe1\xb9\x9bta)", "\xe0\xa4\xb8\xe0\xa4\x82\xe0\xa4\xb8\xe0\xa5\x8d\xe0\xa4\x95\xe0\xa5\x83\xe0\xa4\xa4\xe0\xa4\xae\xe0\xa5\x8d",'Sanskrit');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'SC', "sc", "srd", "srd", "Sardinian", "sardu",'Sardinian');//not yet defined in borhan
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'SD', "sd", "snd", "snd", "Sindhi", "\xe0\xa4\xb8\xe0\xa4\xbf\xe0\xa4\xa8\xe0\xa5\x8d\xe0\xa4\xa7\xe0\xa5\x80, \xd8\xb3\xd9\x86\xda\x8c\xd9\x8a\xd8\x8c \xd8\xb3\xd9\x86\xd8\xaf\xda\xbe\xdb\x8c\xe2\x80\x8e",'Sindhi');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'SE', "se", "sme", "sme", "Northern Sami", "Davvis\xc3\xa1megiella",'Northern Sami');//not yet defined in borhan
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'SM', "sm", "smo", "smo", "Samoan", "gagana fa'a Samoa",'Samoan');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'SG', "sg", "sag", "sag", "Sango", "y\xc3\xa2ng\xc3\xa2 t\xc3\xae s\xc3\xa4ng\xc3\xb6",'Sangro');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'SR', "sr", "srp", "srp", "Serbian", "\xd1\x81\xd1\x80\xd0\xbf\xd1\x81\xd0\xba\xd0\xb8 \xd1\x98\xd0\xb5\xd0\xb7\xd0\xb8\xd0\xba",'Serbian');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'GD', "gd", "gla", "gla", "Scottish Gaelic, Gaelic", "G\xc3\xa0idhlig",'Gaelic (Scottish)');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'SN', "sn", "sna", "sna", "Shona", "chiShona",'Shona');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'SI', "si", "sin", "sin", "Sinhala, Sinhalese", "\xe0\xb7\x83\xe0\xb7\x92\xe0\xb6\x82\xe0\xb7\x84\xe0\xb6\xbd",'Sinhalese');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'SK', "sk", "slk", "slo", "Slovak", "sloven\xc4\x8dina, slovensk\xc3\xbd jazyk",'Slovak');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'SL', "sl", "slv", "slv", "Slovene", "slovenski jezik, sloven\xc5\xa1\xc4\x8dina",'Slovenian');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'SO', "so", "som", "som", "Somali", "Soomaaliga, af Soomaali",'Somali');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'ST', "st", "sot", "sot", "Southern Sotho", "Sesotho",'Sesotho');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'ES', "es", "spa", "spa", "Spanish", "espa\xc3\xb1ol",'Spanish');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'SU', "su", "sun", "sun", "Sundanese", "Basa Sunda",'Sundanese');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'SW', "sw", "swa", "swa", "Swahili", "Kiswahili",'Swahili (Kiswahili)');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'SS', "ss", "ssw", "ssw", "Swati", "SiSwati",'Siswati');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'SV', "sv", "swe", "swe", "Swedish", "svenska",'Swedish');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'TA', "ta", "tam", "tam", "Tamil", "\xe0\xae\xa4\xe0\xae\xae\xe0\xae\xbf\xe0\xae\xb4\xe0\xaf\x8d",'Tamil');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'TE', "te", "tel", "tel", "Telugu", "\xe0\xb0\xa4\xe0\xb1\x86\xe0\xb0\xb2\xe0\xb1\x81\xe0\xb0\x97\xe0\xb1\x81",'Telugu');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'TG', "tg", "tgk", "tgk", "Tajik", "\xd1\x82\xd0\xbe\xd2\xb7\xd0\xb8\xd0\xba\xd3\xa3, to\xc3\xa7ik\xc4\xab, \xd8\xaa\xd8\xa7\xd8\xac\xdb\x8c\xda\xa9\xdb\x8c\xe2\x80\x8e",'Tajik');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'TH', "th", "tha", "tha", "Thai", "\xe0\xb9\x84\xe0\xb8\x97\xe0\xb8\xa2",'Thai');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'TI', "ti", "tir", "tir", "Tigrinya", "\xe1\x89\xb5\xe1\x8c\x8d\xe1\x88\xad\xe1\x8a\x9b",'Tigrinya');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'BO', "bo", "bod", "tib", "Tibetan Standard, Tibetan, Central", "\xe0\xbd\x96\xe0\xbd\xbc\xe0\xbd\x91\xe0\xbc\x8b\xe0\xbd\xa1\xe0\xbd\xb2\xe0\xbd\x82",'Tibetan');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'TK', "tk", "tuk", "tuk", "Turkmen", "T\xc3\xbcrkmen, \xd0\xa2\xd2\xaf\xd1\x80\xd0\xba\xd0\xbc\xd0\xb5\xd0\xbd",'Turkmen');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'TL', "tl", "tgl", "tgl", "Tagalog", "Wikang Tagalog",'Tagalog');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'TN', "tn", "tsn", "tsn", "Tswana", "Setswana",'Setswana');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'TO', "to", "ton", "ton", "Tonga (Tonga Islands)", "faka Tonga",'Tonga');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'TR', "tr", "tur", "tur", "Turkish", "T\xc3\xbcrk\xc3\xa7",'Turkish');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'TS', "ts", "tso", "tso", "Tsonga", "Xitsonga",'Tsonga');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'TT', "tt", "tat", "tat", "Tatar", "\xd1\x82\xd0\xb0\xd1\x82\xd0\xb0\xd1\x80 \xd1\x82\xd0\xb5\xd0\xbb\xd0\xb5, tatar tele",'Tatar');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'TW', "tw", "twi", "twi", "Twi", "Twi",'Twi');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'TY', "ty", "tah", "tah", "Tahitian", "Reo Tahiti",'Tahitian');//not yet defined in borhan
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'UG', "ug", "uig", "uig", "Uyghur", "\xd8\xa6\xdb\x87\xd9\x8a\xd8\xba\xdb\x87\xd8\xb1\xda\x86\xdb\x95\xe2\x80\x8e, Uyghurche",'Uighur');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'UK', "uk", "ukr", "ukr", "Ukrainian", "\xd0\xa3\xd0\xba\xd1\x80\xd0\xb0\xd1\x97\xd0\xbd\xd1\x81\xd1\x8c\xd0\xba\xd0\xb0",'Ukrainian');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'UR', "ur", "urd", "urd", "Urdu", "\xd8\xa7\xd8\xb1\xd8\xaf\xd9\x88",'Urdu');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'UZ', "uz", "uzb", "uzb", "Uzbek", "O\xca\xbbzbek, \xd0\x8e\xd0\xb7\xd0\xb1\xd0\xb5\xd0\xba, \xd8\xa3\xdb\x87\xd8\xb2\xd8\xa8\xdb\x90\xd9\x83\xe2\x80\x8e",'Uzbek');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'VE', "ve", "ven", "ven", "Venda", "Tshiven\xe1\xb8\x93",'Venda');//not yet defined in borhan
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'VI', "vi", "vie", "vie", "Vietnamese", "Ti\xe1\xba\xbfng Vi\xe1\xbb\x87t",'Vietnamese');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'VO', "vo", "vol", "vol", "Volap\xc3\xbck", "Volap\xc3\xbck",'Volapuk');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'WA', "wa", "wln", "wln", "Walloon", "walon",'Walloon');//not yet defined in borhan
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'CY', "cy", "cym", "wel", "Welsh", "Cymraeg",'Welsh');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'WO', "wo", "wol", "wol", "Wolof", "Wollof",'Wolof');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'FY', "fy", "fry", "fry", "Western Frisian", "Frysk",'Frisian');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'XH', "xh", "xho", "xho", "Xhosa", "isiXhosa",'Xhosa');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'YI', "yi", "yid", "yid", "Yiddish", "\xd7\x99\xd7\x99\xd6\xb4\xd7\x93\xd7\x99\xd7\xa9",'Yiddish');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'YO', "yo", "yor", "yor", "Yoruba", "Yor\xc3\xb9",'Yoruba');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'ZA', "za", "zha", "zha", "Zhuang, Chuang", "Sa\xc9\xaf cue\xc5\x8b\xc6\x85, Saw cuengh",'Zhuang, Chuang');//not yet defined in borhan
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'ZU', "zu", "zul", "zul", "Zulu", "isiZulu",'Zulu');

        //The following cases are not part of language ISO , they are added to support backward compatibility
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'SH','sh','shc','shc','Serbo-Croatian','Serbo-Croatian' ,'Serbo-Croatian');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'EN_GB', "en", "enb", "enb", "English (British)", "English (British)",'English (British)');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'EN_US', "en", "enu", "enu", "English (American)", "English (American)",'English (American)');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'MO','mo','mol','mol','Moldavian','Moldavian','Moldavian');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'MU','mu','mul','mul','Multilingual','Multilingual','Multilingual');
        self::addLanguageToArrays($tmpArrTwoCode,$tmpArrThreeCodeT,$tmpArrBorhanName,'UN', "un", "und", "und", "Undefined", "Undefined","Undefined");

        $result = "<?php\n\n".self::assignArrayToVar($tmpArrTwoCode ,'arrayISO639_1');
        $result .= self::assignArrayToVar($tmpArrThreeCodeT, 'arrayISO639_T');
        $result .= self::assignArrayToVar($tmpArrBorhanName ,'arrayBorhanName');
        return $result;
    }

    private static function assignArrayToVar(&$array ,$varName )
    {
        $strArr = var_export($array,true);
        return "self::\$$varName"." = ".$strArr.";\n\n";
    }

}