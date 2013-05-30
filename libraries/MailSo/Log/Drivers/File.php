<?php

namespace MailSo\Log\Drivers;

/**
 * @category MailSo
 * @package Log
 * @subpackage Drivers
 */
class File extends \MailSo\Log\Driver
{
	/**
	 * @var string
	 */
	private $sLoggerFileName;

	/**
	 * @var bool
	 */
	private $bUseWriteCache;

	/**
	 * @var string
	 */
	private $sCrLf;

	/**
	 * @var array
	 */
	private $aWriteCache;

	/**
	 * @access protected
	 *
	 * @param string $sLoggerFileName
	 * @param bool $bUseWriteCache = false
	 * @param string $sCrLf = "\r\n"
	 */
	protected function __construct($sLoggerFileName, $bUseWriteCache = false, $sCrLf = "\r\n")
	{
		parent::__construct();

		$this->sLoggerFileName = $sLoggerFileName;
		$this->bUseWriteCache = $bUseWriteCache;
		$this->sCrLf = $sCrLf;
		$this->aWriteCache = array();
	}

	/**
	 * @param string $sLoggerFileName
	 * @param bool $bUseWriteCache = false
	 * @param string $sCrLf = "\r\n"
	 *
	 * @return \MailSo\Log\Drivers\File
	 */
	public static function NewInstance($sLoggerFileName, $bUseWriteCache = false, $sCrLf = "\r\n")
	{
		return new self($sLoggerFileName, $bUseWriteCache, $sCrLf);
	}

	/**
	 * @param string $sDesc
	 *
	 * @return bool
	 */
	protected function writeImplementation($sDesc)
	{
		$bResult = false;
		if ($this->bUseWriteCache)
		{
			if (0 === count($this->aWriteCache))
			{
				\register_shutdown_function(array(&$this, '__cacheShutDown'));
			}

			$this->aWriteCache[] = $sDesc;
			$bResult = true;
		}
		else
		{
			$bResult = $this->writeToLogFile($sDesc);
		}

		return $bResult;
	}

	/**
	 * @return bool
	 */
	protected function clearImplementation()
	{
		return \unlink($this->sLoggerFileName);
	}

	/**
	 * @param string $sDesc
	 *
	 * @return bool
	 */
	private function writeToLogFile($sDesc)
	{
		return \error_log($sDesc.$this->sCrLf, 3, $this->sLoggerFileName);
	}

	/**
	 * @return bool
	 */
	public function WriteEmptyLine()
	{
		return $this->writeImplementation('');
	}

	/**
	 * @return void
	 */
	public function __cacheShutDown()
	{
		if (0 < count($this->aWriteCache))
		{
			$this->writeToLogFile(\implode($this->sCrLf, $this->aWriteCache));
			$this->aWriteCache = array();
		}
	}
}
