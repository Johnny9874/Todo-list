<?php
/**
 * PEAR_Builder for building PHP extensions (PECL packages)
 *
 * PHP versions 4 and 5
 *
 * @category   pear
 * @package    PEAR
 * @author     Stig Bakken <ssb@php.net>
 * @author     Greg Beaver <cellog@php.net>
 * @copyright  1997-2009 The Authors
 * @license    http://opensource.org/licenses/bsd-license.php New BSD License
 * @link       http://pear.php.net/package/PEAR
 * @since      File available since Release 0.1
 *
 * TODO: log output parameters in PECL command line
 * TODO: msdev path in configuration
 */

/**
 * Needed for extending PEAR_Builder
 */
require_once 'PEAR/Common.php';
require_once 'PEAR/PackageFile.php';
require_once 'System.php';

/**
 * Class to handle building (compiling) extensions.
 *
 * @category   pear
 * @package    PEAR
 * @author     Stig Bakken <ssb@php.net>
 * @author     Greg Beaver <cellog@php.net>
 * @copyright  1997-2009 The Authors
 * @license    http://opensource.org/licenses/bsd-license.php New BSD License
 * @version    Release: 1.10.16
 * @link       http://pear.php.net/package/PEAR
 * @since      Class available since PHP 4.0.2
 * @see        http://pear.php.net/manual/en/core.ppm.pear-builder.php
 */
class PEAR_Builder extends PEAR_Common
{
    var $php_api_version = 0;
    var $zend_module_api_no = 0;
    var $zend_extension_api_no = 0;

    var $extensions_built = array();

    /**
     * @var string Used for reporting when it is not possible to pass function
     *             via extra parameter, e.g. log, msdevCallback
     */
    var $current_callback = null;

    // used for msdev builds
    var $_lastline = null;
    var $_firstline = null;

    /**
     * Parsed --configureoptions.
     *
     * @var mixed[]
     */
    var $_parsed_configure_options;

    /**
     * PEAR_Builder constructor.
     *
     * @param mixed[] $configureoptions
     * @param object $ui user interface object (instance of PEAR_Frontend_*)
     *
     * @access public
     */
    function __construct($configureoptions, &$ui)
    {
        parent::__construct();
        $this->setFrontendObject($ui);
        $this->_parseConfigureOptions($configureoptions);
    }

    /**
     * Parse --configureoptions string.
     *
     * @param string Options, in the form "X=1 Y=2 Z='there\'s always one'"
     */
    function _parseConfigureOptions($options)
    {
        $data = '<XML><PROPERTIES ' . $options . ' /></XML>';
        $parser = xml_parser_create('ISO-8859-1');
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
        xml_set_element_handler(
            $parser, array($this, '_parseConfigureOptionsStartElement'),
            array($this, '_parseConfigureOptionsEndElement'));
        xml_parse($parser, $data, true);
        xml_parser_free($parser);
    }

    /**
     * Handle element start.
     *
     * @see PEAR_Builder::_parseConfigureOptions()
     *
     * @param resource $parser
     * @param string $tagName
     * @param mixed[] $attribs
     */
    function _parseConfigureOptionsStartElement($parser, $tagName, $attribs)
    {
        if ($tagName !== 'PROPERTIES') {
            return;
        }
        $this->_parsed_configure_options = $attribs;
    }

    /**
     * Handle element end.
     *
     * @see PEAR_Builder::_parseConfigureOptions()
     *
     * @param resource
     * @param string $element
     */
    function _parseConfigureOptionsEndElement($parser, $element)
    {
    }

    /**
     * Build an extension from source on windows.
     * requires msdev
     */
    function _build_win32($descfile, $callback = null)
    {
        if (is_object($descfile)) {
            $pkg = $descfile;
            $descfile = $pkg->getPackageFile();
        } else {
            $pf = new PEAR_PackageFile($this->config, $this->debug);
            $pkg = &$pf->fromPackageFile($descfile, PEAR_VALIDATE_NORMAL);
            if (PEAR::isError($pkg)) {
                return $pkg;
            }
        }
        $dir = dirname($descfile);
        $old_cwd = getcwd();

        if (!file_exists($dir) || !is_dir($dir) || !chdir($dir)) {
            return $this->raiseError("could not chdir to $dir");
        }

        // packages that were in a .tar have the packagefile in this directory
        $vdir = $pkg->getPackage() . '-' . $pkg->getVersion();
        if (file_exists($dir) && is_dir($vdir)) {
            if (!chdir($vdir)) {
                return $this->raiseError("could not chdir to " . realpath($vdir));
            }

            $dir = getcwd();
        }

        $this->log(2, "building in $dir");

        $dsp = $pkg->getPackage().'.dsp';
        if (!file_exists("$dir/$dsp")) {
            return $this->raiseError("The DSP $dsp does not exist.");
        }
        // XXX TODO: make release build type configurable
        $command = 'msdev '.$dsp.' /MAKE "'.$pkg->getPackage(). ' - Release"';

        $err = $this->_runCommand($command, array(&$this, 'msdevCallback'));
        if (PEAR::isError($err)) {
            return $err;
        }

        // figure out the build platform and type
        $platform = 'Win32';
        $buildtype = 'Release';
        if (preg_match('/.*?'.$pkg->getPackage().'\s-\s(\w+)\s(.*?)-+/i',$this->_firstline,$matches)) {
            $platform = $matches[1];
            $buildtype = $matches[2];
        }

        if (preg_match('/(.*)?\s-\s(\d+).*?(\d+)/', $this->_lastline, $matches)) {
            if ($matches[2]) {
                // there were errors in the build
                return $this->raiseError("There were errors during compilation.");
            }
            $out = $matches[1];
        } else {
            return $this->raiseError("Did not understand the completion status returned from msdev.exe.");
        }

        // msdev doesn't tell us the output directory :/
        // open the dsp, find /out and use that directory
        $dsptext = join('', file($dsp));

        // this regex depends on the build platform and type having been
        // correctly identified above.
        $regex ='/.*?!IF\s+"\$\(CFG\)"\s+==\s+("'.
                    $pkg->getPackage().'\s-\s'.
                    $platform.'\s'.
                    $buildtype.'").*?'.
                    '\/out:"(.*?)"/is';

        if ($dsptext && preg_match($regex, $dsptext, $matches)) {
            // what we get back is a relative path to the output file itself.
            $outfile = realpath($matches[2]);
        } else {
            return $this->raiseError("Could not retrieve output information from $dsp.");
        }
        // realpath returns false if the file doesn't exist
        if ($outfile && copy($outfile, "$dir/$out")) {
            $outfile = "$dir/$out";
        }

        $built_files[] = array(
            'file' => "$outfile",
            'php_api' => $this->php_api_version,
            'zend_mod_api' => $this->zend_module_api_no,
            'zend_ext_api' => $this->zend_extension_api_no,
            );

        return $built_files;
    }
    // }}}

    // {{{ msdevCallback()
    function msdevCallback($what, $data)
    {
        if (!$this->_firstline)
            $this->_firstline = $data;
        $this->_lastline = $data;
        call_user_func($this->current_callback, $what, $data);
    }

    /**
     * @param string
     * @param string
     * @param array
     * @access private
     */
    function _harvestInstDir($dest_prefix, $dirname, &$built_files)
    {
        $d = opendir($dirname);
        if (!$d)
            return false;

        $ret = true;
        while (($ent = readdir($d)) !== false) {
            if ($ent[0] == '.')
                continue;

            $full = $dirname . DIRECTORY_SEPARATOR . $ent;
            if (is_dir($full)) {
                if (!$this->_harvestInstDir(
                        $dest_prefix . DIRECTORY_SEPARATOR . $ent,
                        $full, $built_files)) {
                    $ret = false;
                    break;
                }
            } else {
                $dest = $dest_prefix . DIRECTORY_SEPARATOR . $ent;
                $built_files[] = array(
                        'file' => $full,
                        'dest' => $dest,
                        'php_api' => $this->php_api_version,
                        'zend_mod_api' => $this->zend_module_api_no,
                        'zend_ext_api' => $this->zend_extension_api_no,
                        );
            }
        }
        closedir($d);
        return $ret;
    }

    /**
     * Build an extension from source.  Runs "phpize" in the source
     * directory, but compiles in a temporary directory
     * (TMPDIR/pear-build-USER/PACKAGE-VERSION).
     *
     * @param string|PEAR_PackageFile_v* $descfile path to XML package description file, or
     *               a PEAR_PackageFile object
     *
     * @param mixed $callback callback function used to report output,
     * see PEAR_Builder::_runCommand for details
     *
     * @return array an array of associative arrays with built files,
     * format:
     * array( array( 'file' => '/path/to/ext.so',
     *               'php_api' => YYYYMMDD,
     *               'zend_mod_api' => YYYYMMDD,
     *               'zend_ext_api' => YYYYMMDD ),
     *        ... )
     *
     * @access public
     *
     * @see PEAR_Builder::_runCommand
     */
    function build($descfile, $callback = null)
    {
        if (preg_match('/(\\/|\\\\|^)([^\\/\\\\]+)?php([^\\/\\\\]+)?$/',
                       $this->config->get('php_bin'), $matches)) {
            if (isset($matches[2]) && strlen($matches[2]) &&
                trim($matches[2]) != trim($this->config->get('php_prefix'))) {
                $this->log(0, 'WARNING: php_bin ' . $this->config->get('php_bin') .
                           ' appears to have a prefix ' . $matches[2] . ', but' .
                           ' config variable php_prefix does not match');
            }

            if (isset($matches[3]) && strlen($matches[3]) &&
                trim($matches[3]) != trim($this->config->get('php_suffix'))) {
                $this->log(0, 'WARNING: php_bin ' . $this->config->get('php_bin') .
                           ' appears to have a suffix ' . $matches[3] . ', but' .
                           ' config variable php_suffix does not match');
            }
        }

        $this->current_callback = $callback;
        if (PEAR_OS == "Windows") {
            return $this->_build_win32($descfile, $callback);
        }

        if (PEAR_OS != 'Unix') {
            return $this->raiseError("building extensions not supported on this platform");
        }

        if (is_object($descfile)) {
            $pkg = $descfile;
            $descfile = $pkg->getPackageFile();
            if (is_a($pkg, 'PEAR_PackageFile_v1')) {
                $dir = dirname($descfile);
            } else {
                $dir = $pkg->_config->get('temp_dir') . '/' . $pkg->getName();
                // automatically delete at session end
                self::addTempFile($dir);
            }
        } else {
            $pf = new PEAR_PackageFile($this->config);
            $pkg = &$pf->fromPackageFile($descfile, PEAR_VALIDATE_NORMAL);
            if (PEAR::isError($pkg)) {
                return $pkg;
            }
            $dir = dirname($descfile);
        }

        // Find config. outside of normal path - e.g. config.m4
        foreach (array_keys($pkg->getInstallationFileList()) as $item) {
          if (stristr(basename($item), 'config.m4') && dirname($item) != '.') {
            $dir .= DIRECTORY_SEPARATOR . dirname($item);
            break;
          }
        }

        $old_cwd = getcwd();
        if (!file_exists($dir) || !is_dir($dir) || !chdir($dir)) {
            return $this->raiseError("could not chdir to $dir");
        }

        $vdir = $pkg->getPackage() . '-' . $pkg->getVersion();
        if (is_dir($vdir)) {
            chdir($vdir);
        }

        $dir = getcwd();
        $this->log(2, "building in $dir");
        $binDir = $this->config->get('bin_dir');
        if (!preg_match('@(^|:)' . preg_quote($binDir, '@') . '(:|$)@', getenv('PATH'))) {
            putenv('PATH=' . $binDir . ':' . getenv('PATH'));
        }
        $err = $this->_runCommand($this->config->get('php_prefix')
                                . "phpize" .
                                $this->config->get('php_suffix'),
                                array(&$this, 'phpizeCallback'));
        if (PEAR::isError($err)) {
            return $err;
        }

        if (!$err) {
            return $this->raiseError("`phpize' failed");
        }

        // {{{ start of interactive part
        $configure_command = "$dir/configure";

        $phpConfigName = $this->config->get('php_prefix')
            . 'php-config'
            . $this->config->get('php_suffix');
        $phpConfigPath = System::which($phpConfigName);
        if ($phpConfigPath !== false) {
            $configure_command .= ' --with-php-config='
                . $phpConfigPath;
        }

        $configure_options = $pkg->getConfigureOptions();
        if ($configure_options) {
            foreach ($configure_options as $option) {
                $default = array_key_exists('default', $option) ? $option['default'] : null;
                if (array_key_exists($option['name'], $this->_parsed_configure_options)) {
                    $response = $this->_parsed_configure_options[$option['name']];
                } else {
                    list($response) = $this->ui->userDialog(
                            'build', [$option['prompt']], ['text'], [$default]);
                }
                if (substr($option['name'], 0, 5) === 'with-' &&
                    ($response === 'yes' || $response === 'autodetect')) {
                    $configure_command .= " --{$option['name']}";
                } else {
                    $configure_command .= " --{$option['name']}=".trim($response);
                }
            }
        }
        // }}} end of interactive part

        // FIXME make configurable
        if (!$user=getenv('USER')) {
            $user='defaultuser';
        }

        $tmpdir = $this->config->get('temp_dir');
        $build_basedir = System::mktemp(' -t "' . $tmpdir . '" -d "pear-build-' . $user . '"');
        $build_dir = "$build_basedir/$vdir";
        $inst_dir = "$build_basedir/install-$vdir";
        $this->log(1, "building in $build_dir");
        if (is_dir($build_dir)) {
            System::rm(array('-rf', $build_dir));
        }

        if (!System::mkDir(array('-p', $build_dir))) {
            return $this->raiseError("could not create build dir: $build_dir");
        }

        self::addTempFile($build_dir);
        if (!System::mkDir(array('-p', $inst_dir))) {
            return $this->raiseError("could not create temporary install dir: $inst_dir");
        }
        self::addTempFile($inst_dir);

        $make_command = getenv('MAKE') ? getenv('MAKE') : 'make';

        $to_run = array(
            $configure_command,
            $make_command,
            "$make_command INSTALL_ROOT=\"$inst_dir\" install",
            "find \"$inst_dir\" | xargs ls -dils"
            );
        if (!file_exists($build_dir) || !is_dir($build_dir) || !chdir($build_dir)) {
            return $this->raiseError("could not chdir to $build_dir");
        }
        putenv('PHP_PEAR_VERSION=1.10.16');
        foreach ($to_run as $cmd) {
            $err = $this->_runCommand($cmd, $callback);
            if (PEAR::isError($err)) {
                chdir($old_cwd);
                return $err;
            }
            if (!$err) {
                chdir($old_cwd);
                return $this->raiseError("`$cmd' failed");
            }
        }
        if (!($dp = opendir("modules"))) {
            chdir($old_cwd);
            return $this->raiseError("no `modules' directory found");
        }
        $built_files = array();
        $prefix = exec($this->config->get('php_prefix')
                        . "php-config" .
                       $this->config->get('php_suffix') . " --prefix");
        $this->_harvestInstDir($prefix, $inst_dir . DIRECTORY_SEPARATOR . $prefix, $built_files);
        chdir($old_cwd);
        return $built_files;
    }

    /**
     * Message callback function used when running the "phpize"
     * program.  Extracts the API numbers used.  Ignores other message
     * types than "cmdoutput".
     *
     * @param string $what the type of message
     * @param mixed $data the message
     *
     * @return void
     *
     * @access public
     */
    function phpizeCallback($what, $data)
    {
        if ($what != 'cmdoutput') {
            return;
        }
        $this->log(1, rtrim($data));
        if (preg_match('/You should update your .aclocal.m4/', $data)) {
            return;
        }
        $matches = array();
        if (preg_match('/^\s+(\S[^:]+):\s+(\d{8})/', $data, $matches)) {
            $member = preg_replace('/[^a-z]/', '_', strtolower($matches[1]));
            $apino = (int)$matches[2];
            if (isset($this->$member)) {
                $this->$member = $apino;
                //$msg = sprintf("%-22s : %d", $matches[1], $apino);
                //$this->log(1, $msg);
            }
        }
    }

    /**
     * Run an external command, using a message callback to report
     * output.  The command will be run through popen and output is
     * reported for every line with a "cmdoutput" message with the
     * line string, including newlines, as payload.
     *
     * @param string $command the command to run
     *
     * @param mixed $callback (optional) function to use as message
     * callback
     *
     * @return bool whether the command was successful (exit code 0
     * means success, any other means failure)
     *
     * @access private
     */
    function _runCommand($command, $callback = null)
    {
        $this->log(1, "running: $command");
        $pp = popen("$command 2>&1", "r");
        if (!$pp) {
            return $this->raiseError("failed to run `$command'");
        }
        if ($callback && $callback[0]->debug == 1) {
            $olddbg = $callback[0]->debug;
            $callback[0]->debug = 2;
        }

        while ($line = fgets($pp, 1024)) {
            if ($callback) {
                call_user_func($callback, 'cmdoutput', $line);
            } else {
                $this->log(2, rtrim($line));
            }
        }
        if ($callback && isset($olddbg)) {
            $callback[0]->debug = $olddbg;
        }

        $exitcode = is_resource($pp) ? pclose($pp) : -1;
        return ($exitcode == 0);
    }

    function log($level, $msg, $append_crlf = true)
    {
        if ($this->current_callback) {
            if ($this->debug >= $level) {
                call_user_func($this->current_callback, 'output', $msg);
            }
            return;
        }
        return parent::log($level, $msg, $append_crlf);
    }
}
